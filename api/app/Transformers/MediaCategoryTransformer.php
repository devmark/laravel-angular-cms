<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\MediaCategory;

class MediaCategoryTransformer extends TransformerAbstract
{
    public function transform(MediaCategory $item)
    {
        return [
            'id'         => (int)$item->id,
            'name'       => $item->name,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];

    }

}



