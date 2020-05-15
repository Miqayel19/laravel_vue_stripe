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
        return $this->belongsToMany('App\User','account_users')->withPivot([
            'role',
            'confirmed',
            'account_token',
            'account_token_generated',
        ]);
    }
}
