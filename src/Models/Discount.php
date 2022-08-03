<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'user_id','code', 'discount_type', 'discount_value', 'buy_qty', 'apply_to', 'get_qty', 'get_what','min_requirement', 'requirement_amount',
        'eligible_customers','eligible_locations','usage_limit','user_limit','usage_count','start_date', 'end_date', 'get_item_id', 'get_item_name',
        'type'
    ];

    public function applicables()
    {
        return $this->hasMany(DiscountApplicable::class, 'discount_code', 'code');
    }

    public function customers()
    {
        return $this->hasMany(DiscountCustomer::class, 'discount_code', 'code');
    }

    public function locations()
    {
        return $this->hasMany(DiscountLocation::class, 'discount_code', 'code');
    }
}
