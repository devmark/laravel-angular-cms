<?php namespace App\Models;

use URL, Storage;

class Media extends BaseModel
{
    protected $table = 'media';

    public $timestamps = true;

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return URL::to('api/media/m/' . $this->attributes['path'] . $this->attributes['key']);
    }

    // this is a recommended way to declare event handlers
    protected static function boot()
    {
        parent::boot();

        // before delete() method call this
        static::deleting(function ($image) {
            Storage::delete('uploads/m/' . $image->path . $image->key);
        });
    }

}
