<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Post;

class PostTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [

    ];

    public function transform(Post $item)
    {
        return [
            'id'           => (int)$item->id,
            'slug'         => $item->slug,
            'title'        => $item->title,
            'content'      => $item->content,

            'status'       => $item->status,
            'visibility'   => $item->visibility,
            'tags'         => is_null($item->tagged) ? [] : array_pluck($item->tagged->toArray(), 'tag_name'),
            'categories'   => array_map('intval', array_pluck($item->categories->toArray(), 'post_category_id')),
            'published_at' => $item->published_at,
            'created_at'   => $item->created_at,
            'updated_at'   => $item->updated_at,
        ];

    }

}


