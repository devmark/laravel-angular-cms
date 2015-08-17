<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Permission;

class PermissionTransformer extends TransformerAbstract
{

    public function transform(Permission $item)
    {
        return [
            'id'           => (int)$item->id,
            'name'         => $item->name,
            'display_name' => $item->display_name,
            'description'  => $item->description,
            'created_at'   => $item->created_at,
            'updated_at'   => $item->updated_at,
        ];
    }
}


