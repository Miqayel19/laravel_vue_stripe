<?php


namespace App\Http\Services;

use App\Http\Contracts\PlanInterface;
use App\Plan;

class PlanService implements PlanInterface
{
    public function __construct(Plan $plan)
    {
        $this->plan = $plan;
    }
    public function index()
    {
        return Plan::all();
    }
    public function show($id)
    {
        return  Plan::where('id',$id)->first();
    }
    public function create($credentials)
    {
        return $this->plan->create($credentials);
    }

    public function update($credentials,$id)
    {
        return $this->plan->where('id', $id)->update($credentials);
    }

}
