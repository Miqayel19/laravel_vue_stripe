<?php

namespace App\Http\Controllers;

use App\plan;
use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\planResource;
use App\Http\Services\planService;
use App\Jobs\SendInvitationEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\planRequest;


class PlanController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = auth('api')->user();
    }
//    /**
//     * Get Authenticated user plans.
//     *
//     * @param planService $planService
//     * @return planResource
//     */
    public function index(planService $planService)
    {
        $plans = $planService->index($this->user->id);
        return new planResource((object)['data' => $plans,'message' =>'Successfully fetched']);
    }
//    /**
//     * GET /api/plan/{id}
//     * Get single plan with id
//     *
//     * @param planService $planService
//     * @param $id
//     * @return FailedResource|planResource
//     */
    public function show(planService $planService,$id)
    {
        $plan = $planService->show($this->user->id,$id);
        if (!$plan) {
            return new FailedResource((object)['error' => 'Sorry, plan with id ' . $id . ' cannot be found']);
        }
        return new planResource((object)['data' => $plan,'message' =>'Successfully fetched']);
    }
//    /**
//     * POST /api/plan/new
//     * Create plan
//     *
//     * @param planService $planService
//     * @param planRequest $request
//     * @return FailedResource|planResource
//     */
    public function store(planService $planService,planRequest $request)
    {
        $credentials = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'status' => "Active"
        ];
        $plan = $planService->create($credentials);
        $planService->attachUser();
        if ($plan) {
            $plans = $planService->index($this->user->id);
            return new planResource((object)['data' => $plans]);
        }
        else {
            return new FailedResource((object)['error' => 'plan can not be added']);
        }
    }
//    /**
//     * PUT /api/plan/{id}
//     * Update plan
//     *
//     * @param planService $planService
//     * @param planRequest $request
//     * @param $id
//     * @return FailedResource|planResource
//     */
    public function update(planService $planService,planRequest $request,$id)
    {
        $credentials = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'status' => "Active"
        ];
        $plan = $planService->update($credentials, $id);
        if ($plan) {
            $plans = $planService->index($this->user->id);
            return new planResource((object)['message' => 'plan  updated','data' => $plans]);
        }
        else {
            return new FailedResource((object)['error' => 'plan can not be updated']);
        }
    }
//    /**
//     * DELETE /api/plan/{id}
//     * Delete plan
//     *
//     * @param planService $planService
//     * @param   $id
//     * @return FailedResource|planResource
//     */
    public function destroy(planService $planService,$id)
    {
        $plan = $planService->show($this->user->id,$id);
        if(!$plan) {
            return new FailedResource((object)['error' => 'Sorry, plan with id ' . $id . ' cannot be found']);
        }
        if ($planService->detachUser()) {
            $plans =  $planService->index($this->user->id);
            return new planResource((object)['message' => 'plan  deleted','data' => $plans]);
        }
        else {
            return new FailedResource((object)['error' => 'plan can not be deleted']);
        }
    }
//    /**
//     * POST /api/plan/sendInvitation
//     * Send invitation to other user for attaching to the plan
//     *
//     * @param Request $request
//     * @return FailedResource|SuccessResource
//     */
    public function invite(Request $request)
    {
        $planToken = Str::random();
        $plan = plan::where('id',$request->acc_id)->first();
        $user  = User::where('email' ,$request->member_email)->first();
        $userplan = $user->plans()->where('plan_id',$request->acc_id)->first();
        if(!$userplan && $plan){
            $plan->users()->attach($user, array("role"=>'member', "confirmed"=>false,'plan_token' => $planToken,'plan_token_generated' => date('Y-m-d H:i:s', time())));
            $info = [];
            $info['plan_id'] = $plan->id;
            $info['email'] = $request->member_email;
            $info['plan_token'] = $planToken;
            SendInvitationEmail::dispatch($info);
            return new SuccessResource((object)['message' => 'Successfully sent!']);
        }
        else{
            return new FailedResource((object)['error' => 'This user already attached to  plan']);
        }
    }
//    /**
//     * POST /api/plan/plan_confirmation/{token}/{id}
//     * Confirmation of Invitation
//     *
//     * @param $token
//     * @param $id
//     * @return FailedResource|SuccessResource
//     */
}
