<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $morphClass = "category";

    protected $fillable = [
        'name','description', 'parent_id', 'active' 
        /* ,'parent_id' */
    ];
    protected $dates = ['deleted_at'];

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

/*  public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    } */

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id', 'id');
    }

    // public function files()
    // {
    //     return $this->hasMany(File::class, 'owner_id', 'id');
    // }
    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }

    public function applicables()
    {
        return $this->morphMany(DiscountApplicable::class, 'applicable');
    }

}
