<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key', 'data'
    ];

    protected $casts = ['data'];
}
