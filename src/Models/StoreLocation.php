<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class StoreLocation extends Model
{

    protected $fillable = [
        'label','country', 'state', 'city', 'tax','address','zip', 'tax_id'
    ];

    public $searchable = [
        'address','country', 'state','city'
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

}
