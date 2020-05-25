<?php


namespace App\Http\Services;

use App\Http\Contracts\SubscriptionInterface;
use App\Subscription;

class SubscriptionService implements SubscriptionInterface
{
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
    public function index()
    {
        return $this->subscription->with('user')->get();
    }
    public function create($credentials)
    {
        return $this->subscription->create($credentials);
    }
}
