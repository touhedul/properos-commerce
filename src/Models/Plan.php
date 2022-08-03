<?php

namespace Properos\Commerce\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'price', 'interval_count', 'interval', 'status', 'item_id'
    ];

    public $searchable = [
        'title'
    ];

    public function toSearchableArray()
    {
        return array_flip($this->searchable);
    }
    
    public function getSearchableArray()
    {
        return $this->searchable;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'current_plan_id', 'id');
    }

    public function plan_items()
    {
        return $this->hasMany(PlanItem::class, 'plan_id', 'id');
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'user_id', 'id')->where('type','plan');
    }
}
