<?php


namespace App\Http\Services;

use App\Http\Contracts\AccountInterface;
use App\Account;
use App\User;

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
    public function delete($id)
    {
        return $this->account->where('id', $id)->delete();
    }

}
