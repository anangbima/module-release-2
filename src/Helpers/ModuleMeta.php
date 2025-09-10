<?php

namespace Modules\ModuleRelease2\Helpers;

class ModuleMeta
{
    public static function get(string $key): ?string
    {
        $meta = [
            'label' => 'Module Release 2',
            'studly' => 'ModuleRelease2',
            'kebab' => 'module-release-2',
            'snake' => 'module_release_2',
            'plain' => 'modulerelease2',
            'constant' => 'MODULE_RELEASE_2',
        ];

        return $meta[$key] ?? null;
    }
}
