<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVariat extends Model
{
    protected $fillable = [
        'item_id','description', 'sku', 'attributes'
    ];
	
	public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
