<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{

    protected $fillable = [
        'label','country', 'state', 'city', 'tax'
    ];

    public $searchable = [
        'label','country', 'state', 'city', 'tax'
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
