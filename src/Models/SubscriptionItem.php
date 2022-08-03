<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionItem extends Model
{
    protected $fillable = [
        'subscription_id', 'item_id', 'qty', 'price','name'
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')->with(['files' => function($q){
            $q->where('owner_type', 'item');
        }]);
    }
}
