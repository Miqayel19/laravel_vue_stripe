<?php


namespace App\Http\Contracts;


interface PlanInterface{
    public function index();
    public function show($id);
    public function create($credentials);
    public function update($credentials,$id);
}
