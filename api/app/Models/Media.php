<?php namespace App\Models;

use URL, Storage;

class Media extends BaseModel
{
    protected $table = 'media';

    public $timestamps = true;
    use TimestampsFormatTrait;
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return URL::to('api/media/m/' . $this->attributes['path'] . $this->attributes['key']);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($image) {
            Storage::delete('uploads/m/' . $image->path . $image->key);
        });
    }

}
