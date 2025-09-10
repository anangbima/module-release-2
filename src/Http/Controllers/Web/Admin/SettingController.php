<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Services\Admin\SettingService;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'General Setting',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'General Setting',
                    'url' => '#',
                ],
            ],
            'settings' => $this->settingService->getAllSetting(),
        ];

        return view(module_release_2_meta('kebab').'::web.admin.setting.index', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->settingService->updateAllSettings($request);

        return redirect()->route(module_release_2_meta('kebab').'.admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
