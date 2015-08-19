<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'roles',
    ];

    public function transform(User $item)
    {
        return [
            'id'            => (int)$item->id,
            'email'         => $item->email,
            'lastname'      => $item->lastname,
            'firstname'     => $item->firstname,
            'phone'         => $item->phone,
            'active'        => (boolean)$item->active,
            'ip'            => $item->ip,
            'timezone'      => $item->timezone,
            'last_login_at' => $item->last_login_at,
            'created_at'    => $item->created_at,
            'updated_at'    => $item->updated_at,
        ];
    }

    public function includeRoles(User $item)
    {
        $roles = $item->roles;

        return $this->collection($roles, new RoleTransformer, null);
    }
}


