<?php


namespace App\Http\Contracts;


interface CartItemInterface{
    public function index();
    public function create($credentials);
    public function delete($id);
}
