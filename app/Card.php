<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'userID','accountID','card_number','fullname','last_four_digits','exp_month','exp_year',
    ];

    public function user(){
        return $this->belongsTo('App\User','userID');
    }
    public function accounts(){
        return $this->belongsTo('App\Account','accountID');
    }
}
