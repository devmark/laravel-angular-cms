<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Role;

class RoleTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'permissions',
    ];

    public function transform(Role $item)
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

    public function includePermissions(Role $item)
    {
        $permissions = $item->perms;

        return $this->collection($permissions, new PermissionTransformer, null);
    }

}


