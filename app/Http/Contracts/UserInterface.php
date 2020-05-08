<?php


namespace App\Http\Contracts;


interface UserInterface{
 public function create($credentials);
 public function update($credentials,$id);
}
