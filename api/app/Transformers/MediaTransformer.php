<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Media;

class MediaTransformer extends TransformerAbstract
{
    public function transform(Media $item)
    {
        return [
            'id'                => (int)$item->id,
            'media_category_id' => (int)$item->media_category_id,
            'path'              => $item->path,
            'url'               => $item->url,
            'filesize'          => (int)$item->filesize,
            'order'             => (int)$item->order,
            'name'              => $item->name,
            'key'               => $item->key,
            'mime'              => $item->mime,
            'created_at'        => $item->created_at,
            'updated_at'        => $item->updated_at,
        ];

    }

}



