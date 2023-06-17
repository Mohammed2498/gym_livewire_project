<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Subscriber;
use App\Models\Subscription;


class SubscriberStats extends Component
{
    public $subscriberCount;
    public $activeCount;
    public $expiredCount;

    public function mount()
    {
        $this->totalSubscribers  = Subscriber::count();
        $this->activeSubscribers  = Subscription::where('status', 'active')->count();
        $this->expiredSubscribers  = Subscription::where('status', 'expired')->count();
    }

    public function render()
    {
        return view('livewire.subscriber-stats')->layout('layouts.admin-layout');
    }
}
