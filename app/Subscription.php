<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'userID','period', 'name','date','renew_date','status','price'
    ];

    public function user(){
        return $this->belongsTo('App\User','userID');
    }
}
