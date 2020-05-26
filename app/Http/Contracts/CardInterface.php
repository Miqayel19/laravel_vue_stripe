<?php


namespace App\Http\Contracts;


interface CardInterface{
    public function index();
    public function show($id);
    public function create($credentials);
    public function update($credentials,$id);
    public function delete($id);
}
