<?php


namespace App\Http\Contracts;


interface OrderInterface{
    public function index();
    public function create($credentials);
}
