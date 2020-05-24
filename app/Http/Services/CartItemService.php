<?php


namespace App\Http\Services;

use App\Http\Contracts\CartItemInterface;
use App\CartItem;

class CartItemService implements CartItemInterface
{
    public function __construct(CartItem $cartItem)
    {
        $this->cartItem = $cartItem;
    }
    public function index()
    {
        return $this->cartItem->with('plan')->get();
    }
    public function delete($id)
    {
        return  $this->cartItem::where('id',$id)->delete();
    }
    public function show($id)
    {
        return  $this->cartItem::where('id',$id)->first();
    }
    public function create($credentials)
    {
        return $this->cartItem->create($credentials);
    }
}
