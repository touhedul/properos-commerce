<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountLocation extends Model
{
    protected $fillable = [
        'discount_id', 'discount_code', 'country_name', 'country_code','state_name', 'state_abbr', 'city_name'
    ];

    public function discounts()
    {
        return $this->hasOne(Discount::class, 'discount_code', 'code');
    }
}
