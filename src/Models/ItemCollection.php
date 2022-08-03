<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCollection extends Model
{

    protected $fillable = [
        'collection_id', 'item_id','sort_order'
    ];

}
