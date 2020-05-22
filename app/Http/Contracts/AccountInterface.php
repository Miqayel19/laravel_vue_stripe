<?php


namespace App\Http\Contracts;


interface AccountInterface{
    public function index($id);
    public function show($userId,$accountID);
    public function create($credentials);
    public function update($credentials,$id);
    public function delete($id);
}
