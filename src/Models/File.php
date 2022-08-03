<?php

namespace Properos\Commerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_type', 'owner_id', 'type', 'label', 'description', 'path', 'thumb_path'
    ];

    protected $dates = ['deleted_at'];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'owner_id');
    }
    public function owner()
    {
        return $this->morphTo();
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'owner_id');
    }

    public static function store($data)
    {
        $file = new File();
        $file->path = $data['path'];
        $file->owner_type = $data['owner_type'];
        $file->owner_id = $data['owner_id'];
        if (array_key_exists('thumb_path', $data)) {
            $file->thumb_path = $data['thumb_path'];
        }
        if (array_key_exists('type', $data)) {
            $file->type = $data['type'];
        }
        $file->save();
        return $file;
    }
}
