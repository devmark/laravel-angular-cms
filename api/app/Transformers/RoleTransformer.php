<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Role;

class RoleTransformer extends TransformerAbstract
{

    public function transform(Role $item)
    {
        return [
            'id'           => (int)$item->id,
            'name'         => $item->name,
            'display_name' => $item->display_name,
            'description'  => $item->description,
            'permissions'  => array_map('intval', array_pluck($item->perms->toArray(), 'pivot.permission_id')),
            'created_at'   => $item->created_at,
            'updated_at'   => $item->updated_at,
        ];
    }
}


