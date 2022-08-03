<?php

namespace Properos\Commerce\Controllers\Subscription;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\CSubscription;
use Properos\Base\Classes\Api;
use Properos\Commerce\Classes\COrder;
use Properos\Commerce\Models\Subscription;
use Properos\Commerce\Models\Plan;
use Properos\Commerce\Jobs\PaySubscription;
use Properos\Users\Models\User;
use Properos\Commerce\Classes\CAccount;
use Properos\Commerce\Jobs\CreateOrderSubscription;
use Properos\Commerce\Models\SubscriptionItem;

class ApiSubscriptionController extends Controller
{
    public function store(Request $request, COrder $cOrder)
    {
        $data = $request->all();
        $cSubcription = new CSubscription($cOrder);
        if(!isset($data['user_id'])){
            $data['user_id'] = \Auth::user()->id;
        }
        $result = $cSubcription->create($data);
        dispatch(new PaySubscription($result['id'], \Auth::user(), $cOrder));
        return response()->json(Api::success('The subscription was successfully created', $result));
    }

    public function change(Request $request, COrder $cOrder)
    {
        $data = $request->all();
        $cSubcription = new CSubscription();

        if(!isset($data['user_id'])){
            $data['user_id'] = \Auth::user()->id;
        }
        $result = $cSubcription->update($data);
        dispatch(new PaySubscription($data['id'], \Auth::user(), $cOrder));
        return response()->json(Api::success('The subscription was successfully changed', $result));
    }

    public function show()
    {
        if(\Auth::check()){
            $result['subscription'] = Subscription::where('user_id', \Auth::user()->id)->with('plan','next_plan')->first();
            $result['plans'] = Plan::get();
            return response()->json(Api::success('Subscription', $result));
        }
        return response()->json(Api::error('004','Forbidden Action', []));
    }

    public function search(Request $request, COrder $cOrder)
    {
        $cSubcription = new CSubscription($cOrder);
        $options = $cSubcription->standardize_search($request);
        $subscriptions = $cSubcription->find($options);
        
        return response()->json(Api::success('Subscriptions search result.', $subscriptions, $cSubcription->get_paginator()->toArray()));
    }

    public function createSubscription(Request $request, COrder $cOrder)
    {
        $data = $request->all();
        $cSubcription = new CSubscription();
        if(!isset($data['user_id'])){
            $data['user_id'] = \Auth::user()->id;
            $user = \Auth::user();
        }else{
            $user = User::where('id',$data['user_id'])->first();
        }
        
        $result = $cSubcription->create($data);
        dispatch(new CreateOrderSubscription($result['id'],$user, $cOrder, \Auth::user()->id));

        return response()->json(Api::success('The subscription was successfully created', $result));
    }

    public function update(Request $request, COrder $cOrder)
    {
        $data = $request->all();
        $cSubcription = new CSubscription();
        return response()->json(Api::success('The subscription was successfully updated', $cSubcription->update($data)));
    }

    public function remove($id)
    {
        if ($id > 0) {
            $cSubs = new CSubscription();
            $cSubs->deleteModel($id);
            SubscriptionItem::where('subscription_id',$id)->delete();
            return response()->json(Api::success('The subscription was successfully deleted', ['id'=>$id]));
        }
        return response()->json(Api::error('006','Invalid Query Format', $id));
    }

}
