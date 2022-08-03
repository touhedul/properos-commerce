<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Properos\Users\Models\User;

class CustomerProfile extends Model
{
    /* use SoftDeletes;
    protected $dates = ['deleted_at']; */

    protected $fillable = [
        'user_id', 'customer_profile_id', 'payment_processor'
    ];

    public function paymentProfiles()
    {
        return $this->hasMany(CustomerPaymentProfile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
