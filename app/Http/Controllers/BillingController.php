<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Http\Contracts\CartItemInterface;
use App\Http\Contracts\OrderInterface;
use App\Http\Contracts\SubscriptionInterface;
use App\Http\Resources\FailedResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    protected $user;
    protected $orderService;
    protected $subscriptionService;
    protected $cartItemService;

    public function __construct(OrderInterface $orderService,SubscriptionInterface $subscriptionService,CartItemInterface $cartItemService)
    {
        $this->user = auth('api')->user();
        $this->orderService = $orderService;
        $this->subscriptionService = $subscriptionService;
        $this->cartItemService = $cartItemService;
    }
//    /**
//     * Get Authenticated user CartItems.
//     *
//     * @return CartItemResource
//     */
    public function getOrders()
    {
        $orders = $this->orderService->index();
        return new OrderResource((object)['data' => $orders,'message' =>'Successfully fetched']);
    }
//    /**
//     * GET /api/CartItem/{id}
//     * Get single CartItem with id
//     *
//     * @param $id
//     * @return FailedResource|CartItemResource
//     */


    public function addOrder(Request $request){

        $totalPrice = 0;
        $cartItems = $this->user->cartItem;
        if($request->account_id) {
            foreach ($cartItems as $item) {
                $totalPrice += $item->plan->price * $item->period;
                $credentialSubscription = [
                    'userID' => $this->user->id,
                    'accountID' => $request->account_id,
                    'price' => $item->plan->price * $item->period,
                    'date' => date('Y-m-d H:i:s', time()),
                    'name' => $item->plan->name,
                    'renew_date' => Carbon::now()->addMonths($item->period)->subDay(),
                    'period' => $item->period,
                    'status' => 'success'

                ];
                $subscription = $this->subscriptionService->create($credentialSubscription);
                $this->cartItemService->delete($item->id);
            }
            $credentialOrder = [
                'userID' => $this->user->id,
                'price' => $totalPrice,
                'accountID' => $request->account_id,
                'date' => date('Y-m-d H:i:s', time()),
                'currency' => 'USD',
                'status' => 'success'
            ];
            $order = $this->orderService->create($credentialOrder);
            $subscription = $this->subscriptionService->index();
            if ($order) {
                return new SuccessResource((object)['message' => 'Successfully  Order added']);
            } else if ($subscription) {
                return new SuccessResource((object)['message' => 'Successfully Subscription  added']);
            }
        } else{
            return new FailedResource((object)['error' => 'Account ID cannot be null, please activate the Account']);
        }
    }

    public function getSubscriptions()
    {
        $subscriptions = $this->subscriptionService->index();
        return new SubscriptionResource((object)['data' => $subscriptions,'message' =>'Successfully fetched']);
    }

}
