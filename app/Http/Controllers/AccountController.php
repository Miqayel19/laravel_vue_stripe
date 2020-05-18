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
    /**
     * Get Authenticated user accounts.
     *
     * @param AccountService $accountService
     * @return AccountResource
     */
    public function index(AccountService $accountService)
    {
        $accounts = $accountService->index($this->user->id);
        return new AccountResource((object)['data' => $accounts,'message' =>'Successfully fetched']);
    }
    /**
     * GET /api/account/{id}
     * Get single account with id
     *
     * @param AccountService $accountService
     * @param $id
     * @return FailedResource|AccountResource
     */
    public function show(AccountService $accountService,$id)
    {
        $account = $accountService->show($this->user->id,$id);
        if (!$account) {
            return new FailedResource((object)['error' => 'Sorry, account with id ' . $id . ' cannot be found']);
        }
        return new AccountResource((object)['data' => $account,'message' =>'Successfully fetched']);
    }
    /**
     * POST /api/account/new
     * Create Account
     *
     * @param AccountService $accountService
     * @param AccountRequest $request
     * @return FailedResource|AccountResource
     */
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
        if ($account) {
            $accounts = $accountService->index($this->user->id);
            return new AccountResource((object)['data' => $accounts]);
        }
        else {
            return new FailedResource((object)['error' => 'Account can not be added']);
        }
    }
    /**
     * PUT /api/account/{id}
     * Update account
     *
     * @param AccountService $accountService
     * @param AccountRequest $request
     * @param $id
     * @return FailedResource|AccountResource
     */
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
        }
        else {
            return new FailedResource((object)['error' => 'Account can not be updated']);
        }
    }
    /**
     * DELETE /api/account/{id}
     * Delete account
     *
     * @param AccountService $accountService
     * @param   $id
     * @return FailedResource|AccountResource
     */
    public function destroy(AccountService $accountService,$id)
    {
        $account = $accountService->show($this->user->id,$id);
        if(!$account) {
            return new FailedResource((object)['error' => 'Sorry, account with id ' . $id . ' cannot be found']);
        }
        if ($accountService->detachUser()) {
            $accounts =  $accountService->index($this->user->id);
            return new AccountResource((object)['message' => 'Account  deleted','data' => $accounts]);
        }
        else {
            return new FailedResource((object)['error' => 'Account can not be deleted']);
        }
    }
    /**
     * POST /api/account/sendInvitation
     * Send invitation to other user for attaching to the account
     *
     * @param Request $request
     * @return FailedResource|SuccessResource
     */
    public function invite(Request $request)
    {
        $accountToken = Str::random();
        $account = Account::where('id',$request->acc_id)->first();
        $user  = User::where('email' ,$request->member_email)->first();
        $userAccount = $user->accounts()->where('account_id',$request->acc_id)->first();
        if(!$userAccount && $account){
            $account->users()->attach($user, array("role"=>'member', "confirmed"=>false,'account_token' => $accountToken,'account_token_generated' => date('Y-m-d H:i:s', time())));
            $info = [];
            $info['account_id'] = $account->id;
            $info['email'] = $request->member_email;
            $info['account_token'] = $accountToken;
            SendInvitationEmail::dispatch($info);
            return new SuccessResource((object)['message' => 'Successfully sent!']);
        }
        else{
            return new FailedResource((object)['error' => 'This user already attached to  account']);
        }
    }
    /**
     * POST /api/account/account_confirmation/{token}/{id}
     * Confirmation of Invitation
     *
     * @param $token
     * @param $id
     * @return FailedResource|SuccessResource
     */
    public function confirm($token,$id)
    {
        $account = Account::where('id', $id)->first();
        if($account){
            $getAccount = $account->users()->where('account_token',$token)->first();
            if($getAccount){
                $end = Carbon::now();
                $start = Carbon::parse($getAccount->account_token_generated);
                $difTime = $end->diffInHours($start);
                if($difTime > 24){
                    $getAccount->update(['confirmed'=>false]);
                    return new FailedResource((object)['error' => 'Account Token time expired']);
                }else {
                    $getAccount->update(['confirmed'=>true]);
                    return new SuccessResource((object)['message' => 'Your Invitation complete,congratulations']);
                }
            }
            else{
                return new FailedResource((object)['message' => 'Account with that token  not found']);
            }
        }
        else{
            return new FailedResource((object)['error' => 'Account not found']);
        }
    }
}
