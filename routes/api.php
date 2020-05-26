<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|

| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/confirmation/{token}', 'AuthController@confirm');
    Route::post('/generate', 'AuthController@generate');
});
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/account', 'AccountController@index');
    Route::get('/account/{id}', 'AccountController@show');
    Route::post('/account/new/', 'AccountController@store');
    Route::put('/account/{id}', 'AccountController@update');
    Route::delete('/account/{id}', 'AccountController@destroy');
    Route::post('/account/sendInvitation', 'AccountController@invite');

    Route::post('/account/account_confirmation/{token}/{id}', 'AccountController@confirm');

    Route::get('/plan', 'PlanController@index');
    Route::get('/plan/{id}', 'PlanController@show');
    Route::post('/plan/new/', 'PlanController@store');
    Route::put('/plan/{id}', 'PlanController@update');
    Route::delete('/plan/{id}', 'PlanController@destroy');

    Route::post('/cartItem/new/{id}', 'CartItemController@store');
    Route::get('/cartItems/', 'CartItemController@index');
    Route::delete('/cartItem/{id}', 'CartItemController@destroy');

    Route::post('/order/new', 'BillingController@addOrder');
    Route::get('/orders/', 'BillingController@getOrders');
    Route::post('/subscription/new/', 'BillingController@addSubscription');
    Route::get('/subscriptions/', 'BillingController@getSubscriptions');

    Route::get('/card', 'CardController@index');
    Route::get('/card/{id}', 'CardController@show');
    Route::post('/card/new/', 'CardController@store');
    Route::put('/card/{id}', 'CardController@update');
    Route::delete('/card/{id}', 'CardController@destroy');



});
