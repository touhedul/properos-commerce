<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Properos\Users\Models\User;

class DiscountCustomer extends Model
{
    protected $fillable = [
        'discount_id', 'discount_code', 'customer_id'
    ];

    public function discounts()
    {
        return $this->hasOne(Discount::class, 'code', 'discount_code');
    }

    public function customers()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }
}
