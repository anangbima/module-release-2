<?php

namespace Modules\DesaModuleTemplate\Helpers;

class ModuleMeta
{
    public static function get(string $key): ?string
    {
        $meta = [
            'label' => 'Desa Module Template',
            'studly' => 'DesaModuleTemplate',
            'kebab' => 'desa-module-template',
            'snake' => 'desa_module_template',
            'plain' => 'desamoduletemplate',
            'constant' => 'DESA_MODULE_TEMPLATE',
        ];

        return $meta[$key] ?? null;
    }
}
