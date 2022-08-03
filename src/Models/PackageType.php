<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{

    protected $fillable = [
        'name', 'weight','weight_unit','width','height','length','dimension_unit'
    ];

}
