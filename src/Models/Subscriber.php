<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Properos\Users\Models\User;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email'
    ];

    protected $dates = ['deleted_at'];

    public $searchable = [
        'email'
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
