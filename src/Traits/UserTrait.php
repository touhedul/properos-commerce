<?php

namespace Properos\Commerce\Traits;

use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\CustomerProfile;
use Properos\Commerce\Models\Subscription;
use Properos\Commerce\Notifications\ResetPasswordCustom;

trait UserTrait{

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class, 'user_id', 'id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_id', 'id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordCustom($token));
    }
}