<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class ItemReview extends Model
{
    protected $fillable = [
        'item_id', 'user_id', 'fullname', 'title','comment', 'rate'
    ];

    public function item()
    {
        return $this->hasOne(Item::class);
    }
}
