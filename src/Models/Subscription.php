<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Properos\Users\Models\User;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'status', 'started_trial_at', 'last_plan_id','current_plan_id', 'next_plan_id', 'last_payment', 
        'last_payment_date', 'next_payment_date', 'expires_at', 'data', 'interval_count', 'interval', 'price',
        'payment_profile_id','days_to_pay','total_payments','payments_made','next_billing_date','grace_days', 'notes'
    ];

    protected $casts = ['data' => 'array'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'current_plan_id');
    }

    public function next_plan()
    {
        return $this->hasOne(Plan::class, 'id', 'next_plan_id');
    }

    public function last_plan()
    {
        return $this->hasOne(Plan::class, 'id', 'last_plan_id');
    }
    public function payment_profile()
    {
        return $this->hasOne(CustomerPaymentProfile::class, 'id', 'paymet_profile_id');
    }

    public function subscription_items()
    {
        return $this->hasMany(SubscriptionItem::class, 'subscription_id', 'id');
    }
}
