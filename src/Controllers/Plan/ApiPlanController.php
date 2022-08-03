<?php

namespace Properos\Commerce\Controllers\Plan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\CPlan;
use Properos\Base\Classes\Api;
use Properos\Commerce\Models\Plan;

class ApiPlanController extends Controller
{
    public function search(Request $request)
    {
        $cPlan = new CPlan();
        $options = $cPlan->standardize_search($request);
        $plans = $cPlan->find($options);
        
        return response()->json(Api::success('Plans search result.', $plans, $cPlan->get_paginator()->toArray()));
    }

    public function store(Request $request)
    {
        $cPlan = new CPlan();
        $data = $request->all();
        $plan = $cPlan->create($data);
        return response()->json(Api::success('The plan was successfully created', $plan));
    }

    public function update($id, Request $request)
    {
        if ($id > 0) {
            $cPlan = new CPlan();
            $data = $request->all();
            $plan = $cPlan->update($data);
        
            return response()->json(Api::success('The plan was successfully updated', $plan));
        }
        return response()->json(Api::error('006','Invalid Query Format', $id));
    }

    public function destroy($id)
    {
        if ($id > 0) {
            $cPlan = new CPlan();
            $cPlan->delete($id);
            return response()->json(Api::success('The plan was successfully deleted', ['id'=>$id]));
        }
        return response()->json(Api::error('006','Invalid Query Format', $id));
    }

    public function getInfoPlan($id){
        if ($id > 0) {
            $plan = Plan::where('id', $id)->with('plan_items.item','discount')->first();
            return response()->json(Api::success('Plan Info', $plan));
        }
        return response()->json(Api::error('006','Invalid Query Format', $id));
    }
}
