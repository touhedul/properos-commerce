<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Properos\Commerce\Models\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description' 
        /* ,'parent_id' */
    ];

    protected $dates = ['deleted_at'];

/*  public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    } */

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
