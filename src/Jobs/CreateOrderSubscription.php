<?php

namespace Properos\Commerce\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Properos\Commerce\Classes\COrder;
use Properos\Commerce\Models\Subscription;
use Properos\Commerce\Models\CustomerProfile;
use Properos\Base\Exceptions\ApiException;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Models\CustomerPaymentProfile;
use Properos\Commerce\Models\Order;

class CreateOrderSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $id;
    protected $user;
    protected $creator_id;
    protected $cOrder;

    public function __construct($id, $user, COrder $cOrder, $creator_id)
    {
        $this->id = $id;
        $this->user = $user;
        $this->creator_id = $creator_id;
        $this->cOrder = $cOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->id;
        $user = $this->user;

        $subscription = Subscription::whereRaw("substr(`next_payment_date`, 1, 10) < '".date('Y-m-d')."'")->where('id',$id)->with('plan', 'next_plan')->with('subscription_items')->first();

        if ($subscription) {
            $items = $subscription->subscription_items->toArray();
            $description = 'Subscription';
            if($subscription->current_plan_id > 0 && ($subscription->next_plan_id == null || $subscription->next_plan_id == 0)){
                $description = $subscription->plan->title.' - '.$subscription->plan->description;
            }else if ($subscription->next_plan_id > 0){
                $description = $subscription->next_plan->title.' - '.$subscription->next_plan->description;
            }
            
            foreach ($items as $key=> $v) {
                $items[$key]['id'] = $v['item_id'];
                if($v['item_id'] == 0){
                    $items[$key]['type'] = 'custom_plan';
                }
                $items[$key]['description'] = isset($v['description']) ? $v['description'] : $description;
                $items[$key]['name'] = 'Subscription -' . $v['name'] .' plan';
            }
            $data = [
                'user_id' => $user->id,
                'creator_id' => $this->creator_id,
                'subscription_id' => $subscription->id,
                'customer_name' => $user->firstname.' '.$user->lastname,
                'customer_email' =>  $user->email,
                'customer_phone' =>  $user->phone,
                'customer_company' =>  $user->company,
                'total_amount' =>  ($subscription->next_plan_id == 0) ? $subscription->price : $subscription->next_plan->price,
                'shipping_status'=>'pending',
                'shipping_urgency'=>'',
                'user'=>$user,
                'order_items'=> $items
            ];

            $customer_profiles = CustomerProfile::where('user_id',$user->id)->first();
            if($customer_profiles && $subscription->next_payment_date <= date('Y-m-d H:i:s')){
                $customer_payment_profile = CustomerPaymentProfile::where([['customer_profile_id', $customer_profiles->id],['id',$subscription->payment_profile_id]])->first();
                if ($customer_payment_profile) {
                    $data['payment_type'] = 'profile'; 
                    $data['type'] = 'subscription';
                    $data['selected_customer_method_id'] = $customer_payment_profile->id;
    
                    try {
                        $result = $this->cOrder->placeOrder($data);
                        
                        $subscription->last_payment = $data['total_amount'];
                        $subscription->last_payment_date = date('Y-m-d H:i:s');
                        if ($subscription->next_plan_id > 0) {
                            $plan = $subscription->next_plan;
                        } else {
                            $plan = $subscription->plan;
                        }
                        if($plan){
                            switch ($plan->interval) {
                                case 'day':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                    break;
                                case 'weekly':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                    break;
                                case 'month':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
                                    break;
                                case 'year':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
                                    break;
                            }
                        }else{
                            switch ($subscription->interval) {
                                case 'day':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                    break;
                                case 'weekly':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                    break;
                                case 'month':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
                                    break;
                                case 'year':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
                                    break;
                            }
                        }
                        // $subscription->expires_at = date('Y-m-d H:i:s', strtotime($subscription->next_payment_date . '+7 day'));
                        
                        if ($subscription->next_plan_id != null && $subscription->next_plan_id > 0) {
                            $subscription->last_plan_id = $subscription->current_plan_id;
                            $subscription->current_plan_id = $subscription->next_plan_id;
                            $subscription->next_plan_id = 0;
                        }
                        
                        if ($subscription->status == 'expired') {
                            $subscription->status = 'active';
                        }
                        $subscription->payments_made = $subscription->payments_made + 1;
                        $subscription->save();
                        
                        \Mail::send('themes.'.Theme::get().'.emails.subscription-payed', ['user'=>$user, 'order' => Order::where('id',$result['id'])->first()->toArray(), 'theme' => Theme::get()], function ($message) use ($user) {
                            $message->to($user->email);
                            $message->subject(env('APP_NAME', 'Properos'). ' - Subscription Payed');
                        });
                    }catch(ApiException $error){
                        if($error->code() == 'E001'){
                            \Mail::send('themes.'.Theme::get().'.emails.payment-declined', ['user'=>$user, 'theme' => Theme::get()], function ($message) use ($user) {
                                $message->to($user->email);
                                $message->subject(env('APP_NAME', 'Properos'). ' - Problems with payment method');
                            });
                        }
                    }
                }else{
                    $result = $this->cOrder->store($data);

                    if($subscription->next_payment_date <= date('Y-m-d H:i:s')){
                        if ($subscription->next_plan_id > 0) {
                            $plan = $subscription->next_plan;
                        } else {
                            $plan = $subscription->plan;
                        }
                        if($plan){
                            switch ($plan->interval) {
                                case 'day':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                    break;
                                case 'weekly':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                    break;
                                case 'month':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
                                    break;
                                case 'year':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
                                    break;
                            }
                        }else{
                            switch ($subscription->interval) {
                                case 'day':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                    break;
                                case 'weekly':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                    break;
                                case 'month':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
                                    break;
                                case 'year':
                                    $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
                                    break;
                            }
                        }
                        $subscription->save();
                        // \Mail::send('themes.'.Theme::get().'.emails.invoice-available', ['user'=>$user, 'order' => $result, 'theme' => Theme::get()], function ($message) use ($user) {
                        //     $message->to($user->email);
                        //     $message->subject(env('APP_NAME', 'Properos'). ' - Invoice Available');
                        // });
                    }
                }
            }else{
                $result = $this->cOrder->store($data);
                if ($subscription->next_payment_date <= date('Y-m-d H:i:s')) {
                    if ($subscription->next_plan_id > 0) {
                        $plan = $subscription->next_plan;
                    } else {
                        $plan = $subscription->plan;
                    }
                    if($plan){
                        switch ($plan->interval) {
                            case 'day':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                break;
                            case 'weekly':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                break;
                            case 'month':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
                                break;
                            case 'year':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
                                break;
                        }
                    }else{
                        switch ($subscription->interval) {
                            case 'day':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                break;
                            case 'weekly':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                break;
                            case 'month':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' month'));
                                break;
                            case 'year':
                                $subscription->next_payment_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subscription->interval_count.' year'));
                                break;
                        }
                    }
                    $subscription->save();
                    // \Mail::send('themes.'.Theme::get().'.emails.invoice-available', ['user'=>$user, 'order' => $result, 'theme' => Theme::get()], function ($message) use ($user) {
                    //     $message->to($user->email);
                    //     $message->subject(env('APP_NAME', 'Properos'). ' - Invoice Available');
                    // });
                }
            }
        }
    }
}
