<?php

use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingTableSeeder extends Seeder
{

    public function run()
    {
        Setting::insert([
                [
                    'type' => 'general', 'name' => 'site_name', 'value' => 'CMS Site',
                ],
                [
                    'type' => 'general', 'name' => 'admin_email', 'value' => 'admin_email',
                ],
                [
                    'type' => 'general', 'name' => 'company_address_line1', 'value' => '',
                ],
                [
                    'type' => 'general', 'name' => 'company_address_line2', 'value' => '',
                ],
                [
                    'type' => 'general', 'name' => 'company_phone', 'value' => '',
                ],
                [
                    'type' => 'general', 'name' => 'timezone', 'value' => 'Asia/Hong_Kong',
                ],
                [
                    'type' => 'general', 'name' => 'active', 'value' => true,
                ],
                [
                    'type' => 'general', 'name' => 'meta_title', 'value' => '',
                ],
                [
                    'type' => 'general', 'name' => 'meta_description', 'value' => '',
                ],
            ]
        );

    }

}