<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPaymentProfile extends Model
{
    /* use SoftDeletes;
    protected $dates = ['deleted_at']; */

    protected $fillable = [
        'customer_profile_id', 'payment_profile_id', 'default', 'last_numbers', 'brand', 'expiration_date'
    ];

    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }
}
