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
    public $duration;
    public $startDate;
    public $endDate;
    public $status;
    public $deleteSubscriptionId;
    public $subscriptionId;
    public $search;
    public $statusFilter = 'all';
    public $subscriptionDays = 0;
    public $updatedPayment;
    public $paymentAmount;
    public $paymentStatus = 'full';
    public $remainingPayment;
    public $genderFilter = 'all'; // Default value is 'all'
    public $price;
    public $subscriptionPrice;



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
        $this->subscribers = Subscriber::with('subscription')->get();
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

        // Determine the default subscription price based on gender
        $this->price = $this->gender === 'male' ? 20.00 : ($this->gender === 'female' ? 30.00 : 0.00);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'price' => $this->price
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
        $price = $this->gender === 'male' ? 20.00 : ($this->gender === 'female' ? 30.00 : 0.00);

        $subscriber->price = $price;
        $subscriber->name = $this->editSubscriberName;
        $subscriber->phone = $this->editSubscriberPhone;
        $subscriber->gender = $this->gender;

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
        $this->resetInputFields();
        $this->dispatchBrowserEvent('showAddSubscriptionModal');
    }

    public function storeSubscription()
    {

        $this->validate([
            'subscriberId' => 'required',
            'duration' => 'required_if:subscriptionType,specified|integer|min:1',
            'paymentStatus' => 'required|in:full,partial,not_paid',
        ]);

        $subscriber = Subscriber::findOrFail($this->subscriberId);
        $defaultSubscriptionPrice = $subscriber->price;


        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths($this->duration);
        $this->subscriptionPrice = $defaultSubscriptionPrice * $this->duration;

        $status = now()->greaterThanOrEqualTo($endDate) ? 'expired' : 'active';

        $subscriptionData = [
            'subscriber_id' => $this->subscriberId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'subscription_price' => $this->subscriptionPrice,
            'duration' => $this->duration,
        ];


        if ($this->paymentStatus === 'full') {
            $this->paymentAmount = $this->subscriptionPrice;
            $this->remainingPayment = 0;
        } elseif ($this->paymentStatus === 'partial') {
            $this->paymentAmount = $this->paymentAmount;
            $this->remainingPayment = $this->subscriptionPrice - $this->paymentAmount;
        } elseif ($this->paymentStatus === 'not_paid') {
            $this->paymentAmount = 0;
            $this->remainingPayment = $this->subscriptionPrice;
        }

        $subscriptionData['payment_status'] = $this->paymentStatus;
        $subscriptionData['payment_amount'] =  $this->paymentAmount;
        $subscriptionData['remaining_payment'] =  $this->remainingPayment;

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

    public function calculateTotalPrice()
    {
        // Validate the duration input if needed
        $this->validate([
            'duration' => 'integer|min:1',
        ]);

        // Calculate the subscription price based on the selected duration
        $subscriber = Subscriber::findOrFail($this->subscriberId);
        $defaultSubscriptionPrice = $subscriber->price;
        $this->subscriptionPrice = $defaultSubscriptionPrice * $this->duration;
    }

    public function updateSubscriptionModal($subscriberId)
    {
        $this->subscriberId = $subscriberId;
        $this->resetInputFields();
        $this->dispatchBrowserEvent('showUpdateSubscriptionModal');
    }

    public function updateSubscription()
    {
        $this->validate([
            'subscriberId' => 'required',
            'duration' => 'required_if:subscriptionType,specified|integer|min:1',
        ]);

        $subscriber = Subscriber::find($this->subscriberId);
        $defaultSubscriptionPrice = $subscriber->price;
        if (!$subscriber) {
            // Handle error: Subscriber not found
            return;
        }

        $subscription = $subscriber->subscription;
        if (!$subscription) {
            // Handle error: Subscription not found
            return;
        }

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths($this->duration);
        $this->subscriptionPrice = $defaultSubscriptionPrice * $this->duration;


        $status = now()->greaterThanOrEqualTo($endDate) ? 'expired' : 'active';

        $subscription->status = $status;
        $subscription->start_date = $startDate;
        $subscription->end_date = $endDate;

        $subscription->payment_amount = $this->updatedPayment;

        if ($this->paymentStatus === 'full') {
            $this->paymentAmount = $this->subscriptionPrice;
            $this->remainingPayment = 0;
        } elseif ($this->paymentStatus === 'partial') {
            $this->paymentAmount = $this->paymentAmount;
            $this->remainingPayment = $this->subscriptionPrice - $this->paymentAmount;
        } elseif ($this->paymentStatus === 'not_paid') {
            $this->paymentAmount = 0;
            $this->remainingPayment = $this->subscriptionPrice;
        }



        $subscription->duration = $this->duration;
        $subscription->payment_amount = $this->paymentAmount;
        $subscription->remaining_payment = $this->remainingPayment;
        $subscription->subscription_price = $this->subscriptionPrice;
        $subscription->payment_status = $this->paymentStatus;

        $subscription->save();

        if (now()->greaterThanOrEqualTo($subscription->end_date)) {
            $subscription->status = 'expired';
            $subscription->save();
        }

        $this->resetInputFields();
        $this->showModal = false;
        $this->dispatchBrowserEvent('subscriptionUpdatedSuccessfully');
    }

    public function updatePaymentAmountModal($subscriberId)
    {
        $this->showModal = true;
        $this->subscriberId = $subscriberId;
        $this->dispatchBrowserEvent('updatePaymentAmountModal');
    }

    public function updatePaymentAmount()
    {
        $this->validate([
            'updatedPayment' => 'numeric',
        ]);

        $subscriber = Subscriber::find($this->subscriberId);

        $subscription = $subscriber->subscription;
        $remainingPayment = $subscription->remaining_payment;

        // Calculate the new remaining payment
        $newRemainingPayment = $remainingPayment - $this->updatedPayment;

        // Update the payment amount and payment status
        $subscription->payment_amount += $this->updatedPayment;

        if ($newRemainingPayment === 0) {
            $subscription->payment_status = 'full';
        } else {
            $subscription->payment_status = 'partial';
        }

        $subscription->remaining_payment = $newRemainingPayment;
        $subscription->save();

        // Update the Livewire property for displaying the remaining payment
        $this->remainingPayment = $newRemainingPayment;

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

    public function updateSubscriptionDays()
    {

        $this->validate([
            'subscriptionDays' => 'integer',
        ]);
        $subscription = Subscription::find($this->subscriberId);

        $endDate = Carbon::parse($subscription->end_date);

        $endDate->addDays($this->subscriptionDays);

        $subscription->end_date = $endDate;
        $subscription->save();
        $this->dispatchBrowserEvent('additionalDaysAdded');
        $this->resetInputFields();
    }
    public function increase()
    {
        $this->subscriptionDays++;
    }

    public function decrease()
    {
        $this->subscriptionDays--;
    }
    private function resetInputFields()
    {
        $this->name = '';
        $this->image = null;
        $this->phone = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->duration = '';
        $this->gender = null;
        $this->subscriptionDays = 0;
    }
}
