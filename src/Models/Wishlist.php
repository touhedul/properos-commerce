<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
	protected $table = 'wishlist';
    protected $fillable = [
        'user_id','item_id' 
    ];
}
 