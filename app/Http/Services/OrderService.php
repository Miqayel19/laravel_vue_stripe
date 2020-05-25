<?php


namespace App\Http\Services;

use App\Http\Contracts\OrderInterface;
use App\Order;

class OrderService implements OrderInterface
{
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    public function index()
    {
        return $this->order->with('user')->get();
    }
    public function create($credentials)
    {
        return $this->order->create($credentials);
    }
}
