<?php

namespace App\Http\Livewire;

use App\Models\Subscriber;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Http\Livewire\NotificationComponent;
use LivewireFlashy\FlashyNotifier;

class ListSubscribers extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $subscribers;
    public $name;
    public $image;
    public $phone;
    public $gender;

    public $subscriber;
    public $subscriberId;
    public $editSubscriberId;
    public $editSubscriberName;
    public $editSubscriberPhone;
    public $deleteSubscriberId;
    public $showModal = false;
    public $subscriptionType;
    public $duration;
    public $startDate;
    public $endDate;
    public $status;
    public $customStartDate;
    public $customEndDate;
    public $deleteSubscriptionId;
    public $subscriptionId;
    public $search;
    public $statusFilter = 'all';
    public $additionalDays;
    public $updatedPayment;
    public $paymentAmount;
    public $paymentStatus;
    public $remainingPayment;
    public $genderFilter = 'all'; // Default value is 'all'

    public function render()
    {
        $subscribers = Subscriber::with('subscription')
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($this->genderFilter !== 'all', function ($query) {
                $query->where('gender', $this->genderFilter);
            })->when($this->statusFilter !== 'all', function ($query) {
                $query->whereHas('subscription', function ($query) {
                    $query->where('status', $this->statusFilter);
                });
            })

            ->paginate(20);


        return view(
            'livewire.list-subscribers',
            ['subscribers' => $subscribers]
        )->layout('layouts.admin-layout');
    }

    public function mount()
    {
        $this->updateSubscriptionStatus();
        $this->listeners += ['notificationReceived' => 'showNotification'];
        $this->subscribers = Subscriber::with('subscription');



    }

    public function updateSubscriptionStatus()
    {
        $subscriptions = Subscription::all();

        foreach ($subscriptions as $subscription) {
            $subscription->status = now()->greaterThanOrEqualTo($subscription->end_date) ? 'expired' : 'active';
            $subscription->save();
        }
    }

    public function addNewSubscriber()
    {

        $this->showModal = false;
        $this->resetInputFields();
        $this->dispatchBrowserEvent('showSubscriberModal');
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function storeSubscriber()
    {
        $this->validate([
            'name' => 'required',
            'phone' => ['required', 'numeric', 'digits_between:10,10', 'regex:/^(056|059)\d{7}$/'], // 'digits_between:10,10', 'regex:/^(056|059)\d{7}$/'
            'gender' => 'required',
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'gender'=>$this->gender
        ];

        if ($this->image) {
            $imagePath = $this->image->store('/', 'subscribers');
            $data['image'] = $imagePath;
        } else {
            $data['image'] = 'users-image.jpg';
        }

        Subscriber::create($data);
        $this->resetInputFields();
        $this->dispatchBrowserEvent('hideSubscriberModal');
        $this->dispatchBrowserEvent('toastrMessage', [
            'type' => 'success',
            'message' => 'Subscriber Added Successfully',
        ]);
    }

    public function editSubscriberModal($SubscriberId)
    {
        $this->showModal = true;
        $subscriber = Subscriber::findOrFail($SubscriberId);
        $this->editSubscriberId = $subscriber->id;
        $this->editSubscriberName = $subscriber->name;
        $this->editSubscriberPhone = $subscriber->phone;
        $this->dispatchBrowserEvent('editSubscriberModal');
    }

    public function editSubscriber()
    {
        $this->showModal = true;
        $subscriber = Subscriber::findOrFail($this->editSubscriberId);
        $this->validate([
            'editSubscriberName' => 'required',
            'editSubscriberPhone' => ['required', 'numeric',],
            'gender' => 'required',
        ]);

        $subscriber->name = $this->editSubscriberName;
        $subscriber->phone = $this->editSubscriberPhone;
        $subscriber->gender=$this->gender;

        if ($this->image) {
            if ($subscriber->image !== "users-image.jpg") {
                Storage::disk('subscribers')->delete($subscriber->image);
            }
            $imagePath = $this->image->store('/', 'subscribers');
            $subscriber->image = $imagePath;
        }
        $subscriber->save();
        $this->resetInputFields();
        $this->dispatchBrowserEvent('SubscriberUpdated');
    }

    public function deleteSubscriberModal($subscriberId)
    {
        $this->showModal = true;
        $this->deleteSubscriberId = $subscriberId;
        $this->dispatchBrowserEvent('deleteSubscriberModal');
    }

    public function deleteSubscriber()
    {
        $subscriber = Subscriber::findOrFail($this->deleteSubscriberId);
        if ($subscriber->image) {
            if ($subscriber->image !== "users-image.jpg") {
                Storage::disk('subscribers')->delete($subscriber->image);
            }
        }
        $subscriber->delete();
        $this->dispatchBrowserEvent('subscriberDeleted');
        session()->flash('message', 'Subscriber deleted successfully!');
    }

    public function createSubscriptionModal($subscriberId)
    {
        $this->resetValidation();
        $this->subscriberId = $subscriberId;
        $this->subscriptionType = 'specified';
        $this->customStartDate = date('Y-m-d');
        $this->customEndDate = date('Y-m-d', strtotime('+1 month'));
        $this->resetInputFields();
        $this->dispatchBrowserEvent('showAddSubscriptionModal');
    }

    public function storeSubscription()
    {
        $this->validate([
            'subscriberId' => 'required',
            'subscriptionType' => 'required',
            'duration' => 'required_if:subscriptionType,specified|integer|min:1',

        ]);

        if ($this->subscriptionType === 'specified') {
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addMonths($this->duration);
        } else {
            $startDate = $this->customStartDate;
            $endDate = $this->customEndDate;
        }

        $status = now()->greaterThanOrEqualTo($endDate) ? 'expired' : 'active';

        $subscriptionData = [
            'subscriber_id' => $this->subscriberId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'subscription_type' => $this->subscriptionType,
            'price' => 20.00,
        ];

        if ($this->subscriptionType === 'specified') {
            $subscriptionData['duration'] = $this->duration;
        }
            // Add payment handling logic
            if ($this->paymentAmount == $subscriptionData['price']) {
                $this->paymentStatus = 'full';
            }
            elseif ($this->paymentAmount > 0) {
                $this->paymentStatus = 'partial';
            }
             else {
                $this->paymentStatus = 'not_paid';
            }

            $remainingPayment = $subscriptionData['price'] - $this->paymentAmount; // Calculate remaining payment
            $subscriptionData['payment_amount'] = $this->paymentAmount;
            $subscriptionData['payment_status'] = $this->paymentStatus;
            $subscriptionData['remaining_payment'] = $remainingPayment;


        Subscription::create($subscriptionData);

        $this->resetInputFields();
        $this->showModal = false;
        $this->dispatchBrowserEvent('subscriptionAddedSuccessfully');

        $subscription = Subscription::where('subscriber_id', $this->subscriberId)->latest()->first();
        if ($subscription && now()->greaterThanOrEqualTo($subscription->end_date)) {
            $subscription->status = 'expired';
            $subscription->save();
        }
    }

    public function updateSubscriptionModal($subscriberId)
    {
        $this->subscriberId = $subscriberId;
        $this->subscriptionType = 'specified';
        $this->customStartDate = date('Y-m-d');
        $this->customEndDate = date('Y-m-d', strtotime('+1 month'));
        $this->resetInputFields();
        $this->dispatchBrowserEvent('showUpdateSubscriptionModal');
    }

    public function updateSubscription()
    {
        $this->validate([
            'subscriberId' => 'required',
            'subscriptionType' => 'required',
            'duration' => 'required_if:subscriptionType,specified|integer|min:1',
        ]);

        $subscriber = Subscriber::find($this->subscriberId);
        if (!$subscriber) {
            // Handle error: Subscriber not found
            return;
        }

        $subscription = $subscriber->subscription;
        if (!$subscription) {
            // Handle error: Subscription not found
            return;
        }
        if ($this->subscriptionType === 'specified') {
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addMonths($this->duration);
        } else {
            // Use the custom start and end dates
            $startDate = $this->customStartDate;
            $endDate = $this->customEndDate;
        }

        $status = now()->greaterThanOrEqualTo($endDate) ? 'expired' : 'active';

        $subscription->status = $status;
        $subscription->start_date = $startDate;
        $subscription->end_date = $endDate;

        $subscription->payment_amount = $this->updatedPayment;

        if ($this->updatedPayment == $subscription->price) {
            $subscription->payment_status = 'full';
            $subscription->remaining_payment = 0;
        } elseif ($this->updatedPayment > 0) {
            $subscription->payment_status = 'partial';
            $subscription->remaining_payment = $subscription->price - $this->updatedPayment;
        } else {
            $subscription->payment_status = 'not_paid';
            $subscription->remaining_payment = $this->updatedPayment;
        }

        if ($this->subscriptionType === 'specified') {
            $subscription->duration = $this->duration;
        } else {
            $subscription->duration = 1;
        }

        $subscription->save();

        if (now()->greaterThanOrEqualTo($subscription->end_date)) {
            $subscription->status = 'expired';
            $subscription->save();
        }

        $this->resetInputFields();
        $this->showModal = false;
        $this->dispatchBrowserEvent('subscriptionUpdatedSuccessfully');
    }

    public function updatePaymentAmountModal($subscriberId){
        $this->showModal = true;
        $this->subscriberId = $subscriberId;
        $this->dispatchBrowserEvent('updatePaymentAmountModal');
    }

    public function updatePaymentAmount(){

        $this->validate([
            'updatedPayment' => 'numeric',
        ]);

        $subscriber = Subscriber::find($this->subscriberId);

        $subscription = $subscriber->subscription;
        $subscription->payment_amount = $this->updatedPayment;

        if ($this->updatedPayment == $subscription->price) {
            $subscription->payment_status = 'full';
            $subscription->remaining_payment=0;
        } elseif ($this->updatedPayment > 0) {
            $subscription->payment_status = 'partial';
            $subscription->remaining_payment=$subscription->price - $this->updatedPayment;
        } else {
            $subscription->payment_status = 'not_paid';
            $subscription->remaining_payment=$this->updatedPayment;
        }

        $subscription->save();

        $this->updatedPayment = null;
        $this->dispatchBrowserEvent('paymentUpdatedSuccessfully');
    }

    public function deleteSubscriptionModal($subscriptionId)
    {

        $this->showModal = true;
        $this->deleteSubscriptionId = $subscriptionId;
        $this->dispatchBrowserEvent('deleteSubscriptionModal');
    }


    public function deleteSubscription()
    {
        $subscription = Subscription::find($this->deleteSubscriptionId);
        if ($subscription) {
            $subscription->delete();
            $this->dispatchBrowserEvent('subscriptionDeleted');
        }
        $this->showModal = false;
    }

    public function addAdditionalDaysModal($subscriberId)
    {

        $this->showModal = true;
        $this->subscriberId = $subscriberId;
        $this->dispatchBrowserEvent('addDiitionalDaysModal');
    }

    public function addAdditionalDays()
    {
        // Validate the input
        $this->validate([
            'additionalDays' => 'required|integer|min:1',
        ]);

        // Get the specific subscriber
        $subscriber = Subscriber::find($this->subscriberId);

        if ($subscriber) {
            // Get the current subscription
            $subscription = $subscriber->subscription;

            // Check if the subscription exists and is active
            if ($subscription && $subscription->status === 'active') {
                // Calculate the new end date by adding the additional days
                $newEndDate = $subscription->end_date->addDays($this->additionalDays);

                // Update the subscription end date and additional days
                $subscription->end_date = $newEndDate;
                $subscription->additional_days += $this->additionalDays;

                $subscription->save();


                // Reset the input field and close the modal
                $this->additionalDays = null;
                $this->dispatchBrowserEvent('additionalDaysAdded');
            }
        }
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->image = null;
        $this->phone = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->subscriptionType = '';
        $this->duration = '';
        $this->gender = null;
    }
}
