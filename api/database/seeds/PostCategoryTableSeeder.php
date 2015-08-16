<?php

use Illuminate\Database\Seeder;


use App\Models\PostCategory;
use App\Models\PostCategoryLanguage;

class PostCategoryTableSeeder extends Seeder
{

    public function run()
    {
        $postCategory = PostCategory::create([
            'parent_id' => null,
            'lft'       => 1,
            'rgt'       => 2,
            'depth'     => 0,
            'active'    => 1,
            'slug'      => 'root',
            'name'      => 'Root'
        ]);
    }

}