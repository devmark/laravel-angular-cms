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

        $allPermissions = [];
        $listRolePermission = Permission::create(['name' => 'roles.index', 'display_name' => 'List Roles']);
        $createRolePermission = Permission::create(['name' => 'roles.store', 'display_name' => 'Create Roles']);
        $editRolePermission = Permission::create(['name' => 'roles.update', 'display_name' => 'Edit Roles']);
        $deleteRolePermission = Permission::create(['name' => 'roles.destroy', 'display_name' => 'Delete Roles']);
        array_push($allPermissions, $listRolePermission->id, $createRolePermission->id, $editRolePermission->id, $listRolePermission->id, $deleteRolePermission->id);

        $listUserPermission = Permission::create(['name' => 'users.index', 'display_name' => 'List Users']);
        $createUserPermission = Permission::create(['name' => 'users.store', 'display_name' => 'Create Users']);
        $editUserPermission = Permission::create(['name' => 'users.update', 'display_name' => 'Edit Users']);
        $deleteUserPermission = Permission::create(['name' => 'users.destroy', 'display_name' => 'Delete Users']);
        array_push($allPermissions, $listUserPermission->id, $createUserPermission->id, $editUserPermission->id, $listUserPermission->id, $deleteUserPermission->id);

        $listPostPermission = Permission::create(['name' => 'posts.index', 'display_name' => 'List Posts']);
        $createPostPermission = Permission::create(['name' => 'posts.store', 'display_name' => 'Create Posts']);
        $editPostPermission = Permission::create(['name' => 'posts.update', 'display_name' => 'Edit Posts']);
        $deletePostPermission = Permission::create(['name' => 'posts.destroy', 'display_name' => 'Delete Posts']);
        array_push($allPermissions, $listPostPermission->id, $createPostPermission->id, $editPostPermission->id, $listPostPermission->id, $deletePostPermission->id);

        $listPostCategoryPermission = Permission::create(['name' => 'posts.categories.index', 'display_name' => 'List Posts Categories']);
        $createPostCategoryPermission = Permission::create(['name' => 'posts.categories.store', 'display_name' => 'Create Posts Categories']);
        $editPostCategoryPermission = Permission::create(['name' => 'posts.categories.update', 'display_name' => 'Edit Posts Categories']);
        $deletePostCategoryPermission = Permission::create(['name' => 'posts.categories.destroy', 'display_name' => 'Delete Posts Categories']);
        array_push($allPermissions, $listPostCategoryPermission->id, $createPostCategoryPermission->id, $editPostCategoryPermission->id, $listPostCategoryPermission->id, $deletePostCategoryPermission->id);

        $listMediaPermission = Permission::create(['name' => 'media.index', 'display_name' => 'List Media']);
        $createMediaPermission = Permission::create(['name' => 'media.store', 'display_name' => 'Create Media']);
        $editMediaPermission = Permission::create(['name' => 'media.update', 'display_name' => 'Edit Media']);
        $deleteMediaPermission = Permission::create(['name' => 'media.destroy', 'display_name' => 'Delete Media']);
        array_push($allPermissions, $listMediaPermission->id, $createMediaPermission->id, $editMediaPermission->id, $listMediaPermission->id, $deleteMediaPermission->id);

        $listMediaCategoryPermission = Permission::create(['name' => 'media.categories.index', 'display_name' => 'List Media Categories']);
        $createMediaCategoryPermission = Permission::create(['name' => 'media.categories.store', 'display_name' => 'Create Media Categories']);
        $editMediaCategoryPermission = Permission::create(['name' => 'media.categories.update', 'display_name' => 'Edit Media Categories']);
        $deleteMediaCategoryPermission = Permission::create(['name' => 'media.categories.destroy', 'display_name' => 'Delete Media Categories']);
        array_push($allPermissions, $listMediaCategoryPermission->id, $createMediaCategoryPermission->id, $editMediaCategoryPermission->id, $listMediaCategoryPermission->id, $deleteMediaCategoryPermission->id);


        $ownerRole->perms()->sync($allPermissions);

        $user = User::create(['firstname' => 'Mark', 'email' => 'admin@emcoo.com', 'password' => Hash::make('adminmark'), 'last_login' => date('Y-m-d H:i:s')]);
        $user->attachRole($ownerRole);

    }

}