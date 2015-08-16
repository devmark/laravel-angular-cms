<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PostCategory;

class PostCategoryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
    ];

    public function transform(PostCategory $item)
    {
        $result = [
            'id'               => (int)$item->id,
            'parent_id'        => (int)$item->parent_id,
            'depth'            => (int)$item->depth,
            'lft'              => (int)$item->lft,
            'rgt'              => (int)$item->rgt,
            'active'           => (boolean)$item->active,

            'slug'             => $item->slug,
            'name'             => $item->name,
            'description'      => $item->description,
            'meta_title'       => $item->meta_title,
            'meta_description' => $item->meta_description,
        ];

        return $result;
    }
}


