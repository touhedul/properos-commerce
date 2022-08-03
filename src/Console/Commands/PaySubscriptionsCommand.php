<?php

namespace Properos\Commerce\Console\Commands;

use Illuminate\Console\Command;
use Properos\Commerce\Models\Subscription;
use Properos\Commerce\Jobs\SubscriptionExpired;
use Properos\Users\Models\User;
use Properos\Base\Exceptions\ApiException;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Models\CustomerProfile;
use Properos\Commerce\Models\CustomerPaymentProfile;
use Properos\Commerce\Classes\COrder;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\OrderItem;

class PaySubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paysubscriptions:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $cOrder;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(COrder $cOrder)
    {
        parent::__construct();
        $this->cOrder = $cOrder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subcriptions =  Subscription::whereRaw("substr(`expires_at`, 1, 10) < '".date('Y-m-d')."'")
                                                ->where(function ($query) {
                                                    $query->where('status', 'trial')
                                                            ->orWhere('status', 'active');
                                                })->get();
            
        if (count($subcriptions)) {
            //Change subscription status to expired and send email to user
            foreach ($subcriptions as $subs) {
                $event_info = [
                    'account_id'=>$subs->account_id,
                    'type'=>'subscriptions',
                    'action'=>'expired',
                    'prev_action'=>$subs->status,
                    'date' => date('Y-m-d'),
                ];
                $subs->status = 'expired';
                $subs->save();
                // dispatch(new SubscriptionExpired(User::where('id',$subs->user_id)->first()));
            }
        }

        $subscriptions = Subscription::whereRaw("substr(`next_billing_date`, 1, 10) <= '".date('Y-m-d')."'")
                                        ->where(function ($query) {
                                            $query->where('status', 'trial')
                                                    ->orWhere('status', 'active');
                                        })->with('plan', 'next_plan')->with('subscription_items')->get();
                                        
            if (count($subscriptions) >0) {
            //Charge subscriptions
            
            foreach($subscriptions as $subs){
                if($subs->total_payments == 0 || $subs->total_payments > $subs->payments_made){
                    $items = $subs->subscription_items->toArray();
                    $user = User::where('id',$subs->user_id)->first();
                    $description = 'Subscription';

                    $order = Order::where('subscription_id',$subs->id)
                                    ->whereRaw("substr(`created_at`, 1, 10) = '".$subs->next_billing_date."'")
                                    ->where([['created_at','<=',$subs->expires_at]])
                                    ->where('paid_amount', 0.00)->first();
                    
                    
                    if ($subs->current_plan_id > 0 && ($subs->next_plan_id == null || $subs->next_plan_id == 0)) {
                        $description = $subs->plan->title.' - '.$subs->plan->description;
                    } elseif ($subs->next_plan_id > 0) {
                        $description = $subs->next_plan->title.' - '.$subs->next_plan->description;
                    }
                    foreach ($items as $key=> $v) {
                        $items[$key]['id'] = $v['item_id'];
                        $items[$key]['description'] = isset($v['description']) ? $v['description'] : $description;
                        $items[$key]['name'] = 'Subscription -' . $v['name'] .' plan';
                        if ($subs->current_plan_id == 0) {
                            $items[$key]['type'] = 'custom_plan';
                        }
                    }
                    
                    
                    $data = [
                        'user_id' => $user->id,
                        'creator_id' => 0,
                        'subscription_id' => $subs->id,
                        'customer_name' => $user->firstname.' '.$user->lastname,
                        'customer_email' =>  $user->email,
                        'customer_phone' =>  $user->phone,
                        'customer_company' =>  $user->company,
                        'total_amount' =>  ($subs->next_plan_id == 0) ? $subs->price : $subs->next_plan->price,
                        'shipping_status'=>'pending',
                        'shipping_urgency'=>'',
                        'user'=>$user,
                        'order_items'=> $items,
                        'selected_customer_method_id' => ($subs->payment_profile_id > 0) ? $subs->payment_profile_id : null,
                    ];
                    // dd($order);
                    if (!$order) {
                        // $data['id'] = $order->id;
                        // OrderItem::where('order_id',$order->id)->delete();
                    }
                    
                    $customer_profiles = CustomerProfile::where('user_id', $user->id)->first();
                    if ($customer_profiles || $subs->payment_profile_id > 0) {
                        $customer_payment_profile = CustomerPaymentProfile::where([['customer_profile_id', $customer_profiles->id],['default',1]])->first();
                        if ($customer_payment_profile || $subs->payment_profile_id > 0) {
                            $data['payment_type'] = 'profile';
                            $data['type'] = 'subscription';

                            try {
                                if ($subs->next_plan_id > 0) {
                                    $plan = $subs->next_plan;
                                } else {
                                    $plan = $subs->plan;
                                }
                                if ($plan) {
                                    switch ($plan->interval) {
                                        case 'day':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                            break;
                                        case 'weekly':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                            break;
                                        case 'month':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' month'));
                                            break;
                                        case 'year':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' year'));
                                            break;
                                    }
                                }else{
                                    switch ($subs->interval) {
                                        case 'day':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                            break;
                                        case 'weekly':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                            break;
                                        case 'month':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' month'));
                                            break;
                                        case 'year':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' year'));
                                            break;
                                    }
                                }
                                $subs->save();
                                // dd($data);
                                $result = $this->cOrder->placeOrder($data);
            
                                $subs->last_payment = $data['total_amount'];
                                // $subs->last_payment_date = date('Y-m-d H:i:s');
                                
                                // $subs->expires_at = date('Y-m-d H:i:s', strtotime($subs->next_billing_date . '+7 day'));
                                
                                if ($subs->next_plan_id != null && $subs->next_plan_id > 0) {
                                    $subs->last_plan_id = $subs->current_plan_id;
                                    $subs->current_plan_id = $subs->next_plan_id;
                                    $subs->next_plan_id = 0;
                                }
                                
                                if ($subs->status == 'expired') {
                                    $subs->status = 'active';
                                }

                                $last_order = Order::where('subscription_id', $subs->id)->where('status', 'pending')->first();
                                if(!$last_order){
                                    $subs->next_payment_date = $subs->next_billing_date;
                                }
                                $subs->save();
                                // \Mail::send('themes.'.Theme::get().'.emails.subscription-payed', ['user'=>$user, 'order' => $result, 'theme' => Theme::get()], function ($message) use ($user) {
                                //     $message->to($user->email);
                                //     $message->subject(env('APP_NAME', 'Properos'). ' - Subscription Payed');
                                // });
                            } catch (ApiException $error) {
                                if ($error->code() == 'E001') {
                                    // \Mail::send('themes.'.Theme::get().'.emails.payment-declined', ['user'=>$user, 'theme' => Theme::get()], function ($message) use ($user) {
                                    //     $message->to($user->email);
                                    //     $message->subject(env('APP_NAME', 'Properos'). ' - Problems with payment method');
                                    // });
                                }
                            }
                        } else {
                            $result = $this->cOrder->store($data);
                            if (!$order) {
                                if ($subs->next_plan_id > 0) {
                                    $plan = $subs->next_plan;
                                } else {
                                    $plan = $subs->plan;
                                }
                                if($plan){
                                    switch ($plan->interval) {
                                        case 'day':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                            break;
                                        case 'weekly':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                            break;
                                        case 'month':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' month'));
                                            break;
                                        case 'year':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' year'));
                                            break;
                                    }
                                }else{
                                    switch ($subs->interval) {
                                        case 'day':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                            break;
                                        case 'weekly':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                            break;
                                        case 'month':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' month'));
                                            break;
                                        case 'year':
                                            $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' year'));
                                            break;
                                    }
                                }
                                $subs->save();
                                // \Mail::send('themes.'.Theme::get().'.emails.invoice-available', ['user'=>$user, 'order' => $result, 'theme' => Theme::get()], function ($message) use ($user) {
                                //     $message->to($user->email);
                                //     $message->subject(env('APP_NAME', 'Properos'). ' - Invoice Available');
                                // });
                            }
                        }
                    } else {
                        $result = $this->cOrder->store($data);
                        if (!$order) {
                            if ($subs->next_plan_id > 0) {
                                $plan = $subs->next_plan;
                            } else {
                                $plan = $subs->plan;
                            }
                            if($plan){
                                switch ($plan->interval) {
                                    case 'day':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                        break;
                                    case 'weekly':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                        break;
                                    case 'month':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' month'));
                                        break;
                                    case 'year':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' year'));
                                        break;
                                }
                            }else{
                                switch ($subs->interval) {
                                    case 'day':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 day'));
                                        break;
                                    case 'weekly':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 days'));
                                        break;
                                    case 'month':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' month'));
                                        break;
                                    case 'year':
                                        $subs->next_billing_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+'.$subs->interval_count.' year'));
                                        break;
                                }
                            }
                            $subs->save();
                            // \Mail::send('themes.'.Theme::get().'.emails.invoice-available', ['user'=>$user, 'order' => $result, 'theme' => Theme::get()], function ($message) use ($user) {
                            //     $message->to($user->email);
                            //     $message->subject(env('APP_NAME', 'Properos'). ' - Invoice Available');
                            // });
                        }
                    }

                    
                }
            }
        }

    }
}
