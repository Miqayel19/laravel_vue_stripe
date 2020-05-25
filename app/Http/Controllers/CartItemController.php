<?php

namespace App\Http\Controllers;

use App\Http\Resources\FailedResource;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\PriceResource;
use App\Http\Contracts\CartItemInterface;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;



class CartItemController extends Controller
{

    protected $user;
    protected $cartItemService;
    public function __construct(CartItemInterface $cartItemService)
    {
        $this->user = auth('api')->user();
        $this->cartItemService = $cartItemService;
    }
//    /**
//     * Get Authenticated user CartItems.
//     *
//     * @return CartItemResource
//     */
    public function index()
    {
        $totalPrice = 0;
        $cartItems = $this->cartItemService->index();
        foreach ($cartItems as $item){
            $item->planPrice = $item->plan->price * $item->period;
            $totalPrice += $item->plan->price * $item->period;
        }
        return new PriceResource((object)['data' => $cartItems,'message' =>'Successfully fetched','total' => $totalPrice]);
    }
//    /**
//     * GET /api/CartItem/{id}
//     * Get single CartItem with id
//     *
//     * @param $id
//     * @return FailedResource|CartItemResource
//     */


    public function store(Request $request,$id){
        $credentials = [
            'userID' => $this->user->id,
            'planID' => $id,
            'accountID' => $request->account_id,
            'period' => $request->period,
        ];
        if($request->account_id) {
            $cartItem = $this->cartItemService->create($credentials);
            if ($cartItem) {
                return new SuccessResource((object)['message' => 'Successfully added']);
            }
        }
        else{
            return new FailedResource((object)['error' => 'Account ID cannot be null, please activate the Account']);

        }
    }

//    /**
//     * DELETE /api/CartItem/{id}
//     * Delete CartItem
//     *
//     * @param   $id
//     * @return FailedResource|CartItemResource
//     */
    public function destroy($id)
    {
        $cartItem = $this->cartItemService->show($id);
        if(!$cartItem) {
            return new FailedResource((object)['error' => 'Sorry, CartItem with id ' . $id . ' cannot be found']);
        }
        if ($cartItem->delete($id)) {
            $cartItems =  $this->cartItemService->index();
            return new CartItemResource((object)['message' => 'CartItem  deleted','data' => $cartItems]);
        }
        else {
            return new FailedResource((object)['error' => 'CartItem can not be deleted']);
        }
    }

}
