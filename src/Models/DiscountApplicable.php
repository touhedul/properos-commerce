<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountApplicable extends Model
{
    protected $fillable = [
        'discount_id', 'discount_code', 'applicable_id', 'applicable_type'
    ];

    public function applicable()
    {
        return $this->morphTo();
    }

    public function discounts()
    {
        return $this->hasOne(Discount::class, 'discount_code', 'code');
    }

}
