<?php

namespace Modules\DesaModuleTemplate\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DesaModuleTemplate\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $generalSettings = [
            ['key' => 'site_name', 'value' => 'Desa Module Template', 'type' => 'string'],
            ['key' => 'maintenance_mode', 'value' => false, 'type' => 'boolean'],
            ['key' => 'max_upload_size', 'value' => 2048, 'type' => 'integer'],
            ['key' => 'supported_languages', 'value' => json_encode(['en', 'fr', 'es']), 'type' => 'array'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'type' => 'string'],
        ];

        $socialMediaSettings = [
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/desamoduletemplate', 'type' => 'string'],
            ['key' => 'twitter_handle', 'value' => '@desamoduletemplate', 'type' => 'string'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/desamoduletemplate', 'type' => 'string'],
        ];

        $logoSettings = [
            ['key' => 'logo_light', 'value' => 'uploads/logo-light.png', 'type' => 'string'],
            ['key' => 'logo_dark', 'value' => 'uploads/logo-dark.png', 'type' => 'string'],
            ['key' => 'favicon', 'value' => 'uploads/favicon.ico', 'type' => 'string'],
        ];

        $allSettings = [];

        foreach ($generalSettings as $setting) {    
            $allSettings[] = array_merge($setting, ['group' => 'general']);
        }

        foreach ($socialMediaSettings as $setting) {
            $allSettings[] = array_merge($setting, ['group' => 'social-media']);
        }

        foreach ($logoSettings as $setting) {
            $allSettings[] = array_merge($setting, ['group' => 'logo']);
        }

        foreach ($allSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                ]
            );
        }
    }
}
