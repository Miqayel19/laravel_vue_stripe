<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\AccountResource;
use App\Http\Services\AccountService;
use App\Jobs\SendInvitationEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;


class AccountController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    public function index(AccountService $accountService)

    {
        $accounts = $accountService->index($this->user->id);
        return new AccountResource((object)['data' => $accounts,'message' =>'Successfully fetched']);
    }

    public function show(AccountService $accountService,$id)
    {
        $account = $accountService->show($this->user->id,$id);
        if (!$account) {
            return new SuccessResource((object)['message' => 'Sorry, account with id ' . $id . ' cannot be found']);
        }
        return new AccountResource((object)['data' => $account,'message' =>'Successfully fetched']);
    }

    public function store(AccountService $accountService,AccountRequest $request)
    {
        $credentials = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'status' => "Active"
        ];
        $account = $accountService->create($credentials);
        $accountService->attachUser();
//        $account->users()->attach($this->user, array("role"=>'owner', "confirmed"=>true,'account_token' => Str::random(),'account_token_generated' => date('Y-m-d H:i:s', time())));
        if ($account) {
            $accounts = $accountService->index($this->user->id);
            return new AccountResource((object)['data' => $accounts]);
        }else {
            return new FailedResource((object)['message' => 'Account can not be added']);
        }
    }
    public function update(AccountService $accountService,AccountRequest $request,$id)
    {
        $credentials = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'status' => "Active"
        ];
        $account = $accountService->update($credentials, $id);
        if ($account) {
            $accounts = $accountService->index($this->user->id);
            return new AccountResource((object)['message' => 'Account  updated','data' => $accounts]);
        }else {
            return new FailedResource((object)['message' => 'Account can not be updated']);
        }
    }
    public function destroy(AccountService $accountService,$id)
    {
        $account = $accountService->show($this->user->id,$id);
        if(!$account) {
            return new FailedResource((object)['message' => 'Sorry, account with id ' . $id . ' cannot be found']);
        }
        if ($accountService->detachUser()) {
            $accounts =  $accountService->index($this->user->id);
            return new AccountResource((object)['message' => 'Account  deleted','data' => $accounts]);
        } else {
            return new FailedResource((object)['message' => 'Account can not be deleted']);
        }
    }
    public function invite(Request $request)
    {
        $accountToken = Str::random();
        $account = Account::where('id',$request->acc_id)->first();
        $user  = User::where('email' ,$request->member_email)->first();
        $userAccount = $user->accounts()->where('account_id',$request->acc_id)->first();
        if(!$userAccount && $account){
            $account->users()->attach($user, array("role"=>'member', "confirmed"=>false,'account_token' => $accountToken,'account_token_generated' => date('Y-m-d H:i:s', time())));
        }
        $info = [];
        $info['account_id'] = $account->id;
        $info['email'] = $request->member_email;
        $info['account_token'] = $accountToken;
        $info['email'] = $request->member_email;
        SendInvitationEmail::dispatch($info);
        return new SuccessResource((object)['message' => 'Successfully sent!']);
    }
    public function confirm($token,$id)
    {
        $account = Account::where('id', $id)->first();
        if($account){
            $getAccount = $account->users()->where('account_token',$token)->first();
            if($getAccount){
                $end = Carbon::now();
                $start = Carbon::parse($getAccount->account_token_generated);
                $dif_time = $end->diffInHours($start);
                if($dif_time > 24){
                    $getAccount->update(['confirmed'=>false]);
                    return new FailedResource((object)['message' => 'Account Token time expired']);
                }else {
                    $getAccount->update(['confirmed'=>true]);
                    return new SuccessResource((object)['message' => 'Your Invitation complete,congratulations']);
                }
            }
        }
        else{
            return new FailedResource((object)['message' => 'Account not found']);
        }
    }
}
