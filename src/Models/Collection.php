<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

    protected $fillable = [
        'name', 'slug'
    ];

    public $searchable = [
        'name'
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

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_collections', 'collection_id','item_id')->withPivot('sort_order');
    }

}
