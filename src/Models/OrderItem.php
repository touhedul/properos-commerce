<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'order_id',
        'item_id',
        'name',
        'description',
        'sku_id',
        'qty',
        'price',
        'marketplace_id',
        'marketplace_item_id',
        'taxes',
        'discount_percent',
        'options'
    ];
    
    protected $dates = ['deleted_at'];

    protected $casts = ['options' => 'array'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')->with(['files' => function($q){
            $q->where('owner_type', 'item');
        }]);
    }
    public function item_with_image()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')->with(['files' => function($q){
            $q->where([['owner_type', 'item'],['type','image']]);
        }]);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
