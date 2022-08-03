<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketplaceItem extends Model
{
    /* use SoftDeletes; */

    protected $fillable = [
        'item_id', 'name', 'marketplace_id', 'price', 'qty'
    ];

    /* protected $dates = ['deleted_at']; */

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
