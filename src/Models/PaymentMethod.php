<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','label', 'description'
    ];

    protected $dates = ['deleted_at'];
	
	public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
