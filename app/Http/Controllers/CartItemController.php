<?php

namespace App\Http\Controllers;

use App\Http\Services\CartItemService;
use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\CartItemResource;
use Carbon\Carbon;


class CartItemController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = auth('api')->user();
    }
//    /**
//     * Get Authenticated user CartItems.
//     *
//     * @param CartItemService $CartItemService
//     * @return CartItemResource
//     */
    public function index(CartItemService $cartItemService)
    {
        $cartItems = $cartItemService->index();
        return new CartItemResource((object)['data' => $cartItems,'message' =>'Successfully fetched']);
    }
//    /**
//     * GET /api/CartItem/{id}
//     * Get single CartItem with id
//     *
//     * @param CartItemService $CartItemService
//     * @param $id
//     * @return FailedResource|CartItemResource
//     */


    public function store(CartItemService $cartItemService,$id){
        $credentials = [
            'userID' => $this->user->id,
            'planID' => $id
        ];
        $cartItem = $cartItemService->create($credentials);
        if($cartItem){
            $cartItems = $cartItemService->index();
            return new CartItemResource((object)['data' => $cartItems,'message' =>'Successfully added']);
        }
    }
    public function show(CartItemService $cartItemService,$id)
    {
        $cartItem = $cartItemService->show($id);
        if (!$cartItem) {
            return new FailedResource((object)['error' => 'Sorry, CartItem with id ' . $id . ' cannot be found']);
        }
        return new CartItemResource((object)['data' => $cartItem,'message' =>'Successfully fetched']);
    }

//    /**
//     * DELETE /api/CartItem/{id}
//     * Delete CartItem
//     *
//     * @param CartItemService $CartItemService
//     * @param   $id
//     * @return FailedResource|CartItemResource
//     */
    public function destroy(CartItemService $cartItemService,$id)
    {
        $cartItem = $cartItemService->show($id);
        if(!$cartItem) {
            return new FailedResource((object)['error' => 'Sorry, CartItem with id ' . $id . ' cannot be found']);
        }
        if ($cartItem->delete()) {
            $cartItems =  $cartItemService->index();
            return new CartItemResource((object)['message' => 'CartItem  deleted','data' => $cartItems]);
        }
        else {
            return new FailedResource((object)['error' => 'CartItem can not be deleted']);
        }
    }

}
