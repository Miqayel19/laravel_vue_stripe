<?php


namespace App\Http\Contracts;


interface SubscriptionInterface{
    public function index();
    public function create($credentials);
}
