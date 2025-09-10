<?php

namespace Modules\ModuleRelease2\Services\Admin;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Services\Shared\SettingService as SharedSettingService;

class SettingService extends SharedSettingService
{
    /**
     * Set a setting by key.
     */
    public function setSetting(string $key, mixed $value, string $type = 'string')
    {
        return $this->settingRepository->set($key, $value, $type);
    }

    /**
     * Update all settings.
     */
    public function updateAllSettings(Request $request)
    {
        $settings = $this->getAllSetting();

        foreach ($settings as $setting) {
            $key = $setting->key;
            $type = $setting->type;
            
            $value = $request->has($key)
                ? $request->input($key)
                : ($type === 'boolean' ? false : null);

            $this->setSetting($key, $value, $type);
        }

        // Log the activity
        $this->logActivityService->log(
            action: 'update_settings',
            model: null,
            description: sprintf(
                'System settings have been updated. A total of %d setting(s) were modified.',
                $settings->count()
            ),
            before: $settings->pluck('value', 'key')->toArray(),
            after: $request->all()
        );

    }

}