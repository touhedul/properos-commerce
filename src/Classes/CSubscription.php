<?php

namespace Properos\Commerce\Classes;

use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Subscription;
use Properos\Commerce\Models\Plan;
use Properos\Commerce\Jobs\PaySubscription;
use Properos\Commerce\Models\SubscriptionItem;

/**
 * Description of
 *
 * @author @RAHG
 */

class CSubscription extends Base
{
    protected $cOrder;
    public function __construct()
    {
        // $this->cOrder = $cOrder;
        parent::__construct(Subscription::class, '');
    }

    public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            switch ($key) {
                case 'last_plan_id':
                case 'plan_id':
                case 'next_plan_id':
                // case 'users':
                case 'last_payment':
                // case 'account_id':
                    $this->fillable[$key] = '0';
                    break;
                case 'status':
                    $this->fillable[$key] = 'trial';
                    break;
                case 'expires_at':
                case 'next_payment_date':
                case 'last_payment_date':
                case 'started_trial_at':
                case 'data':
                    $this->fillable[$key] = null;
                    break;
                default:
                    $this->fillable[$key] = '';
                    break;
            }
        }
    }

    public function create(array $data)
    {
        $rules = [
            'user_id' => 'required|integer',
            'status' => 'nullable|in:expired,trial,active,locked',
        ];

        if(isset($data['current_plan_id']) && $data['current_plan_id'] > 0){
            $plan = Plan::where('id', $data['current_plan_id'])->with('plan_items')->with('discount')->first();
            if($plan){
                $data['data'] = $plan;
                $data['interval_count'] = $plan->interval_count;
                $data['interval'] = $plan->interval;
                $data['price'] = $plan->price;
            }
        }
        if(isset($data['expires_at'])){
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime($data['expires_at']));
        }else{
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
        }
        
        
        $result = $this->createModel($data, $rules);
       
        if(isset($plan)){
            $subItem = new SubscriptionItem();
            $subItem->subscription_id = $result['id'];
            $subItem->item_id = $plan->item_id;
            $subItem->name =  $plan->title;
            $subItem->qty = 1;
            $subItem->price = $plan->price;
            $subItem->save();
            if(count($plan->plan_items)> 0){
                foreach($plan->plan_items as $item){
                    $subItem = new SubscriptionItem();
                    $subItem->subscription_id = $result['id'];
                    $subItem->item_id = $item['id'];
                    $subItem->name = $item['name'];
                    $subItem->qty = $item['qty'];
                    $subItem->price = 0.00;
                    $subItem->save();
                }
            }
        }else if(isset($data['items_list']) && count($data['items_list'])>0){
            foreach($data['items_list'] as $item){
                $subItem = new SubscriptionItem();
                $subItem->subscription_id = $result['id'];
                $subItem->item_id = $item['id'];
                $subItem->name = $item['name'];
                $subItem->qty = $item['qty'];
                $subItem->price = $item['price'];
                $subItem->save();
            }
        }else{
            $subItem = new SubscriptionItem();
            $subItem->subscription_id = $result['id'];
            $subItem->item_id = 0;
            $subItem->name = 'Custom Plan';
            $subItem->qty = 1;
            $subItem->price = $data['price'];
            $subItem->save();
        }
        return $result;
    }

    public function update(array $data)
    {
        $rules = [
            'id' => 'required|integer',
            'user_id' => 'required|integer',
            'status' => 'nullable|in:expired,trial,active,locked',
        ];

        SubscriptionItem::where('subscription_id',$data['id'])->delete();

        if(isset($data['current_plan_id']) && $data['current_plan_id'] > 0){
            $plan = Plan::where('id', $data['current_plan_id'])->with('plan_items')->with('discount')->first();
            if($plan){
                $data['data'] = $plan;
                $subItem = new SubscriptionItem();
                $subItem->subscription_id = $data['id'];
                $subItem->item_id = $plan->item_id;
                $subItem->name =  $plan->title;
                $subItem->qty = 1;
                $subItem->price = $plan->price;
                $subItem->save();

                foreach($plan->plan_items as $item){
                    $subItem = new SubscriptionItem();
                    $subItem->subscription_id = $data['id'];
                    $subItem->item_id = $item['id'];
                    $subItem->name = $item['name'];
                    $subItem->qty = $item['qty'];
                    $subItem->price = 0.00;
                    $subItem->save();
                }
            }
        }else if(isset($data['items_list']) && count($data['items_list'])>0){
            foreach($data['items_list'] as $item){
                $subItem = new SubscriptionItem();
                $subItem->subscription_id = $data['id'];
                $subItem->item_id = $item['id'];
                $subItem->name = $item['name'];
                $subItem->qty = $item['qty'];
                $subItem->price = $item['price'];
                $subItem->save();
            }

        }else{
            $subItem = new SubscriptionItem();
            $subItem->subscription_id = $data['id'];
            $subItem->item_id = 0;
            $subItem->name = 'Custom Plan';
            $subItem->qty = 1;
            $subItem->price = $data['price'];
            $subItem->save();
        }
        if(isset($data['expires_at'])){
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime($data['expires_at']));
        }else if($data['next_payment_date']){
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime($data['next_payment_date'] . '+7 days'));
        }
        $update = $this->updateModel($data, $rules);

        return $update;
    }

    // public function create(array $data)
    // {
    //     $rules = [
    //         'user_id' => 'required|integer',
    //         'status' => 'nullable|in:expired,trial,active,locked',
    //     ];
    //     if(isset($data['current_plan_id']) && isset($data['current_plan_id']) > 0){
    //         $plan = Plan::where('id', $data['current_plan_id'])->with('plan_items')->with('discount')->first();
    //         if($plan){
    //             $data['data'] = json_encode($plan);
    //         }
    //     }

    //     $result =  $this->createModel($data, $rules);
    //     dispatch(new PaySubscription($result['id'], \Auth::user(), $this->cOrder));
        
    //     if(isset($plan) && count($plan->plan_items) > 0){
    //         foreach($plan->plan_items as $item){
    //             $subItem = new SubscriptionItem();
    //             $subItem->subscription_id = $result['id'];
    //             $subItem->item_id = $item['id'];
    //             $subItem->qty = $item['qty'];
    //             $subItem->price = 0.00;
    //             $subItem->save();
    //         }
    //     }
        
    //     return $result;
    // }
    
    // public function change(array $data)
    // {
    //     $rules = [
    //         'id' => 'required|integer',
    //         'user_id' => 'required|integer',
    //         'plan_id'=> 'nullable|integer',
    //         'status' => 'nullable|in:expired,trial,active,locked',
    //     ];

    //     SubscriptionItem::where('subscription_id',$data['id'])->delete();

    //     if(isset($data['plan_id']) && isset($data['plan_id']) > 0){
    //         $plan = Plan::where('id', $data['plan_id'])->with('plan_items')->with('discount')->first();
    //         if($plan){
    //             $data['data'] = json_encode($plan);
    //         }
    //     }
        
    //     $update = $this->updateModel($data, $rules);

    //     dispatch(new PaySubscription($data['id'], \Auth::user(), $this->cOrder));
    //     return $update;
    // }

    

    

}
