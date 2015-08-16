<?php namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Admin;

class AdminTransformer extends TransformerAbstract
{

    public function transform(Admin $item)
    {
        return [
            'id'              => (int)$item->id,
            'email'           => $item->email,
            'lastname'        => $item->lastname,
            'firstname'       => $item->firstname,
            'phone'           => $item->phone,
            'active'          => (boolean)$item->active,
            'activation_code' => $item->activation_code,
            'ip'              => $item->ip,
            'timezone'        => $item->timezone,
            'last_login_at'   => $item->last_login_at,
            'created_at'      => $item->created_at,
            'updated_at'      => $item->updated_at,
        ];
    }
}


