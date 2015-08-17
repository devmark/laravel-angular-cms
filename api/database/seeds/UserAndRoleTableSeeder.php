<?php

use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
class UserAndRoleTableSeeder extends Seeder
{

    public function run()
    {
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'owner']);
        $listPostPermission = Permission::create(['name' => 'list-post', 'display_name' => 'List Posts']);
        $createPostPermission = Permission::create(['name' => 'create-post', 'display_name' => 'Create Posts']);
        $editPostPermission = Permission::create(['name' => 'edit-post', 'display_name' => 'Edit Posts']);
        $deletePostPermission = Permission::create(['name' => 'delete-post', 'display_name' => 'Delete Posts']);
        $ownerRole->perms()->sync([$listPostPermission->id, $createPostPermission->id, $editPostPermission->id, $deletePostPermission->id]);


        $user = User::create(['firstname' => 'Mark', 'email' => 'admin@emcoo.com', 'password' => Hash::make('adminmark'), 'last_login' => date('Y-m-d H:i:s')]);
        $user->attachRole($ownerRole);

    }

}