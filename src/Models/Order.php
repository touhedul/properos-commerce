<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Properos\Users\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'paid_amount',
        'shipping_addres_1',
        'shipping_addres_2',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
        'shipping_method',
        'shipping_method_code',
        'shipping_status',
        'shipping_urgency',
        'shipping_tracking',
        'shipping_cost',
        'shipping_cost_estimated',
        'status',
        'customer_name',
        'customer_email',
        'marketplace_created_at',
        'notes',
        'partial',
        'min_payment',
        'discount_code',
        'discount_amount',
        'subtotal',
        'tax',
        'tax_base',
        'store_id',
        'refund_amount',
        'creator_id',
        'subscription_id'
    ];

    protected $dates = ['deleted_at'];

    public $searchable = [
        'order_number', 'customer_email','customer_name'
    ];

    public $index_fulltext = false;

    public function toSearchableArray()
    {
        return array_flip($this->searchable);
    }
    
    public function getSearchableArray()
    {
        return $this->searchable;
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function (Order $order) {
            $order->orderItems()->delete();
        });

        self::restored(function (Order $order) {
            $order->orderItems()->restore();
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class)->with('item');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

}