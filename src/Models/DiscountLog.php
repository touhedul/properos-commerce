<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountLog extends Model
{
    protected $fillable = [
        'discount_id','user_id', 'discount_code', 'action', 'discount_data'
    ];
}
