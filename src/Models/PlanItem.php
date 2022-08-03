<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class PlanItem extends Model
{
    protected $fillable = [
        'plan_id', 'item_id', 'qty', 'price','name'
    ];

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')->with(['files' => function($q){
            $q->where('owner_type', 'item');
        }]);
    }

}
