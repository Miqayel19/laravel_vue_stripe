<?php

namespace App;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable = [
        'name', 'email', 'password','auth_token','token_generated','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function accounts()
    {
        return $this->belongsToMany('App\Account','account_users','user_id','account_id')->withPivot([
            'role',
            'confirmed',
            'account_token',
            'account_token_generated',
        ]);
    }
    public function cartItem(){
        return $this->hasMany('App\CartItem','userID');
    }
    public function order(){
        return $this->hasMany('App\Order','userID');
    }
    public function subscription(){
        return $this->hasMany('App\Subscription','userID');
    }
}
