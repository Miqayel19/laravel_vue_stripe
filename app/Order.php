<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'userID','currency', 'price','date','status','accountID'
    ];

    public function user(){
        return $this->belongsTo('App\User','userID');
    }
}
