<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AdminTableSeeder::class);
        $this->command->info('Admin table seeded!');

        $this->call(SettingTableSeeder::class);
        $this->command->info('Setting table seeded!');

        $this->call(PostCategoryTableSeeder::class);
        $this->command->info('PostCategory table seeded!');

//        $this->call(SettingTableSeeder::class);
//        $this->command->info('Setting table seeded!');

        Model::reguard();
    }
}
