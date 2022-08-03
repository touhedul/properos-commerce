<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $morphClass = "item";

    protected $fillable = [
        'name', 'short_name', 'description', 'location_id', 'price', 'msrp',
        'mpn', 'um', 'weight', 'weight_unit', 'sku','last_sku', 'status' , 'total_qty',
        'publish_date', 'category_id', 'cost', 'barcode', 'length', 'length_unit',
        'height', 'height_unit', 'width', 'width_unit' ,'package_type', 
        'upc', 'taxable', 'tax_percent', 'active', 'reviews_total', 'reviews_percent', 'type','options','variants'
    ];
    
    protected $dates = ['deleted_at'];
    protected $casts = ['package_type' => 'array','options' => 'array', 'variants' => 'array'];

    public $searchable = [
        'name','sku'
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
	
	public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }

    public function images()
    {
        return $this->morphMany(File::class, 'owner')->where('type','image');
    }
    public function barcode()
    {
        return $this->morphMany(File::class, 'owner')->where('type','barcode');
    }
    
	public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function itemVariants()
    {
        return $this->hasMany(ItemVariat::class);
    }

    public function marketplaceItems()
    {
        return $this->hasMany(MarketplaceItem::class);
    }

    public function item_reviews()
    {
        return $this->hasMany(ItemReview::class);
    }

    public function applicables()
    {
        return $this->morphMany(DiscountApplicable::class, 'applicable');
    }
    public function item_cart()
    {
        return $this->hasMany(CartItem::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'item_collections', 'item_id','collection_id')->withPivot('sort_order');
    }
}
