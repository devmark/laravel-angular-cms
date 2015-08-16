<?php namespace App\Models;

//use App\Models\Relations\BelongsToCustomerTrait;
use Carbon;
use App\Models\Language;

class Post extends BaseModel
{
    use \Conner\Tagging\TaggableTrait;
    use PublishedAtTimestampsFormatTrait;
    protected $table = 'post';

    public $timestamps = true;
    protected $with = [];

    public function categories()
    {
        return $this->belongsToMany('App\Models\PostCategory', 'post_post_category');
    }

}
