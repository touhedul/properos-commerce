<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id', 'item_id', 'qty', 'order_id','options'
    ];

    protected $casts = ["options" => "array"];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
