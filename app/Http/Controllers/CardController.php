<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Contracts\CardInterface;
use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\CardResource;
use App\Http\Requests\CardRequest;
use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;

class CardController extends Controller
{

    protected $user;
    protected $cardService;
    public function __construct(CardInterface $cardService)
    {
        $this->user = auth('api')->user();
        $this->cardService = $cardService;
    }
//    /**
//     * Get Authenticated user cards.
//     *
//     * @param cardService $cardService
//     * @return cardResource
//     */
    public function index()
    {
        $cards =  $this->cardService->index();
        return new CardResource((object)['data' => $cards,'message' =>'Successfully fetched']);
    }
//    /**
//     * GET /api/card/{id}
//     * Get single card with id
//     *
//     * @param cardService $cardService
//     * @param $id
//     * @return FailedResource|cardResource
//     */
    public function show($id)
    {
        $card =  $this->cardService->show($id);
        if (!$card) {
            return new FailedResource((object)['error' => 'Sorry, card with id ' . $id . ' cannot be found']);
        }
        return new CardResource((object)['data' => $card,'message' =>'Successfully fetched']);
    }
//    /**
//     * POST /api/card/new
//     * Create card
//     *
//     * @param cardService $cardService
//     * @param cardRequest $request
//     * @return FailedResource|cardResource
//     */
    public function store(CardRequest $request)
    {
        if ($request->account_id) {
            $active_account = $this->user->accounts()->where('account_id', $request->account_id)->first();
            if ($active_account->stripe_customer_id) {
                $stripe = Stripe::make(config('services.stripe.secret'));
//                dd(var_dump($request->exp_month));
                $cardStripe = $stripe->cards()->create($active_account->stripe_customer_id, [
                    'number' => $request->card_number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                ]);
//                dd($cardStripe);
                $credentials = [
                    'userID' => $this->user->id,
                    'accountID' => $request->account_id,
                    'fullname' => $request->fullname,
                    'cardID' => $cardStripe['id'],
                    'last_four_digits' => $cardStripe['last4'],
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,

                ];
                $card = $this->cardService->create($credentials);
                if ($card) {
                    $cards = $this->cardService->index();
                    return new CardResource((object)['data' => $cards, 'message' => 'Added Successfully']);
                } else {
                    return new FailedResource((object)['error' => 'card can not be added']);
                }
            } else {
                $stripe = Stripe::make(config('services.stripe.secret'));
                $customer = $stripe->customers()->create([
                    'email' => 'test@test.com',
                    'name' => 'Test Customer'
                ]);
                $active_account->stripe_customer_id = $customer['id'];
                $active_account->save();
            }
        } else {
            return new FailedResource((object)['error' => 'AccountID cannot be null, please select the account first']);
        }
    }
//    /**
//     * PUT /api/card/{id}
//     * Update card
//     *
//     * @param cardService $cardService
//     * @param cardRequest $request
//     * @param $id
//     * @return FailedResource|cardResource
//     */
    public function update(CardRequest $request,$id)
    {
        $credentials = [
            'name' => $request->name,
            'price' => $request->price,
        ];
        $card =  $this->cardService->update($credentials, $id);
        if ($card) {
            $cards =  $this->cardService->index();
            return new CardResource((object)['message' => 'card  updated','data' => $cards]);
        }
        else {
            return new FailedResource((object)['error' => 'card can not be updated']);
        }
    }
//    /**
//     * DELETE /api/card/{id}
//     * Delete card
//     *
//     * @param cardService $cardService
//     * @param   $id
//     * @return FailedResource|cardResource
//     */
    public function destroy($id)
    {
        $card =  $this->cardService->show($id);
        if(!$card) {
            return new FailedResource((object)['error' => 'Sorry, card with id ' . $id . ' cannot be found']);
        }
        if ( $this->cardService->delete($id)) {
            $cards =   $this->cardService->index();
            return new cardResource((object)['message' => 'Card  deleted','data' => $cards]);
        }
        else {
            return new FailedResource((object)['error' => 'Card can not be deleted']);
        }
    }
}
