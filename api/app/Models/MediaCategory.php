<?php namespace App\Models;

class MediaCategory extends BaseModel
{
    protected $table = 'media_category';

    public $timestamps = true;

    public function media()
    {
        return $this->hasMany('App\Models\Media', 'media_category_id', 'id');
    }

    // this is a recommended way to declare event handlers
    protected static function boot()
    {
        parent::boot();

        // before delete() method call this
        static::deleting(function ($category) {
            foreach ($category->media as $block) {
                $block->delete();
            }
        });
    }

}
