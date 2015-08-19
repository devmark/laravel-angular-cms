<?php namespace App\Models;

class MediaCategory extends BaseModel
{
    protected $table = 'media_category';
    use TimestampsFormatTrait;
    public $timestamps = true;

    public function media()
    {
        return $this->hasMany('App\Models\Media', 'media_category_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($category) {
            foreach ($category->media as $block) {
                $block->delete();
            }
        });
    }

}
