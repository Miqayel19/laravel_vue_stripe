<?php


namespace App\Http\Contracts;


interface CartItemInterface{
    public function index();
    public function show($id);
    public function create($credentials);
}
