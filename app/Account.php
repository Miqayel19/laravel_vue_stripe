<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'address', 'phone','country','status','currency','stripe_customer_id'
    ];
    public function users()
    {
        return $this->belongsToMany('App\User','account_users','account_id','user_id')->withPivot([
            'role',
            'confirmed',
            'account_token',
            'account_token_generated',
        ]);
    }
    public function orders(){
        return $this->hasMany('App\Order','accountID');
    }
    public function cartItems(){
        return $this->hasMany('App\CartItem','accountID');
    }
    public function subscriptions(){
        return $this->hasMany('App\Subscription','accountID');
    }


}
