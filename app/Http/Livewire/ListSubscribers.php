<?php

namespace App\Http\Livewire;

use App\Models\Subscriber;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class ListSubscribers extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $subscribers;
    public $name;
    public $image;
    public $phone;
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
    public $status ;
    public $customStartDate;
    public $customEndDate;
    public $deleteSubscriptionId;
    public $subscriptionId;


    public function render()
    {
        $subscribers = Subscriber::all();
        return view('livewire.list-subscribers', ['subscribers' => $subscribers])->layout('layouts.admin-layout');
    }

    public function mount()
    {
        $this->subscribers = Subscriber::with('subscription');
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
            'phone' => ['required', 'numeric',]// 'digits_between:10,10', 'regex:/^(056|059)\d{7}$/'
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
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
            'editSubscriberPhone' => ['required', 'numeric',]// 'digits_between:10,10', 'regex:/^(056|059)\d{7}$/'
        ]);

        $subscriber->name = $this->editSubscriberName;
        $subscriber->phone = $this->editSubscriberPhone;


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
            $status = $endDate->isPast() ? 'expired' : 'active';
            $subscriptionData = ([
                'subscriber_id' => $this->subscriberId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'subscription_type' => $this->subscriptionType,
            ]);
            if ($this->duration) {
                $subscriptionData['duration'] = $this->duration;
            }
        } else {
            // Use the custom start and end dates
            $startDate = $this->customStartDate;
            $endDate = $this->customEndDate;
            $status = now()->greaterThan($endDate) ? 'expired' : 'active';
            $subscriptionData = ([
                'subscriber_id' => $this->subscriberId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $status,
                'subscription_type' => $this->subscriptionType,
            ]);
        }

        Subscription::create($subscriptionData);
        $this->resetInputFields();
        $this->showModal = false;
        $this->dispatchBrowserEvent('subscriptionAddedSuccessfully');

        // Update status to "expired" if the end date is already passed
        $subscription = Subscription::find($this->subscriberId);
        if ($subscription && now()->greaterThan($subscription->end_date)) {
            $subscription->update(['status' => 'expired']);
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
            $status = $endDate->isPast() ? 'expired' : 'active';
        } else {
            // Use the custom start and end dates
            $startDate = $this->customStartDate;
            $endDate = $this->customEndDate;
            $status = now()->greaterThan($endDate) ? 'expired' : 'active';
        }

        $subscription->start_date = $startDate;
        $subscription->end_date = $endDate;
        $subscription->status = $status;


        if ($this->subscriptionType === 'specified') {
            $subscription->duration = $this->duration;
        } else {
            $subscription->duration = 1;
        }
        $subscription->subscription_type = $this->subscriptionType;
        $subscription->save();
        $this->resetInputFields();
        $this->showModal = false;
        $this->dispatchBrowserEvent('subscriptionUpdatedSuccessfully');
        // Update status to "expired" if the end date is already passed
        $subscription = Subscription::find($this->subscriberId);
        if ($subscription && now()->greaterThan($subscription->end_date)) {
            $subscription->update(['status' => 'expired']);
        }
    }

    public function deleteSubscriptionModal($subscriptionId)
    {

        $this->showModal = true;
        $this->deleteSubscriptionId = $subscriptionId;

        $this->dispatchBrowserEvent('deleteSubscriptionModal');
    }
    public function deleteSubscription(){
        $subscription = Subscription::find($this->deleteSubscriptionId);
        if ($subscription) {
            $subscription->delete();
            $this->dispatchBrowserEvent('subscriptionDeleted');
        }
        $this->showModal = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->image = null;
        $this->phone = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->subscriptionType = 'specified';
        $this->duration = '';
    }
}
