<?php namespace App\Models;

//use App\Models\Relations\BelongsToCustomerTrait;

class PostCategory extends \Baum\Node
{
    use TimestampsFormatTrait;


    protected $table = 'post_category';

    public $timestamps = true;
    protected $hidden = ['lft', 'rgt'];

    protected $with = [];

    public function posts()
    {
        return $this->belongsToMany('App\Models\Post', 'post_post_category');
    }

}
