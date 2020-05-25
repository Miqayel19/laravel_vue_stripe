<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'userID', 'planID', 'period','accountID'
    ];
    public function plan()
    {
        return $this->belongsTo('App\Plan', 'planID');
    }
    public function user(){
        return $this->belongsTo('App\User','userID');
    }
}
