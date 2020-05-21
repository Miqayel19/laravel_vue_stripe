<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Http\Services\CartItemService;
use App\Plan;
use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\PlanResource;
use App\Http\Services\PlanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\PlanRequest;


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
    public function index(PlanService $planService)
    {
        $plans = $planService->index();
        return new PlanResource((object)['data' => $plans,'message' =>'Successfully fetched']);
    }
//    /**
//     * GET /api/plan/{id}
//     * Get single plan with id
//     *
//     * @param planService $planService
//     * @param $id
//     * @return FailedResource|planResource
//     */
    public function show(PlanService $planService,$id)
    {
        $plan = $planService->show($id);
        if (!$plan) {
            return new FailedResource((object)['error' => 'Sorry, plan with id ' . $id . ' cannot be found']);
        }
        return new PlanResource((object)['data' => $plan,'message' =>'Successfully fetched']);
    }
//    /**
//     * POST /api/plan/new
//     * Create plan
//     *
//     * @param planService $planService
//     * @param planRequest $request
//     * @return FailedResource|planResource
//     */
    public function store(PlanService $planService,planRequest $request)
    {
        $credentials = [
            'name' => $request->name,
            'price' => $request->price,
        ];
        $plan = $planService->create($credentials);
        if ($plan) {
            $plans = $planService->index();
            return new PlanResource((object)['data' => $plans,'message' => 'Added Successfully']);
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
    public function update(PlanService $planService,PlanRequest $request,$id)
    {
        $credentials = [
            'name' => $request->name,
            'price' => $request->price,
        ];
        $plan = $planService->update($credentials, $id);
        if ($plan) {
            $plans = $planService->index();
            return new PlanResource((object)['message' => 'plan  updated','data' => $plans]);
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
    public function destroy(PlanService $planService,$id)
    {
        $plan = $planService->show($id);
        if(!$plan) {
            return new FailedResource((object)['error' => 'Sorry, plan with id ' . $id . ' cannot be found']);
        }
        if ($plan->delete()) {
            $plans =  $planService->index();
            return new PlanResource((object)['message' => 'plan  deleted','data' => $plans]);
        }
        else {
            return new FailedResource((object)['error' => 'plan can not be deleted']);
        }
    }
}
