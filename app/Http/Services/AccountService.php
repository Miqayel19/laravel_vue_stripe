<?php


namespace App\Http\Services;

use App\Http\Contracts\AccountInterface;
use App\Account;
use App\User;
use Illuminate\Support\Str;

class AccountService implements AccountInterface
{
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    public function index($userId)
    {
        $authUser = User::where('id',$userId)->first();

        return $authUser->accounts;
    }
    public function show($userId,$accountID)
    {
        $authUser = User::where('id',$userId)->first();

        return $authUser->accounts->find($accountID);
    }
    public function create($credentials)
    {
        return $this->account->create($credentials);
    }

    public function update($credentials,$id)
    {
        return $this->account->where('id', $id)->update($credentials);
    }
    public function attachUser()
    {
        return $this->account->users()->attach($this->account, array("role"=>'owner', "confirmed"=>true,'account_token' => Str::random(),'account_token_generated' => date('Y-m-d H:i:s', time())));
    }
    public function detachUser()
    {
        return $this->account->users()->detach($this->account, array("role"=>'owner', "confirmed"=>true,'account_token' => Str::random(),'account_token_generated' => date('Y-m-d H:i:s', time())));
    }
}
