<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'price', 'userID'
    ];
    public function users()
    {
        return $this->belongsTo('App\User','userID');
    }
}
