<?php

namespace Modules\DesaModuleTemplate\Providers\Concerns;

trait LoadModelConfigs
{
    protected function loadModelConfigs(): void
    {
        $configDir = base_path('modules/desa-module-template/config');

        if (!is_dir($configDir)) return;

        foreach (scandir($configDir) as $modelDir) {
            $modelPath = $configDir . "/$modelDir";

            if (!is_dir($modelPath) || in_array($modelDir, ['.', '..'])) continue;

            foreach (glob($modelPath . '/*.php') as $filePath) {
                $filename = basename($filePath, '.php'); // e.g. table, uploads

                $this->mergeConfigFrom(
                    $filePath,
                    "desa_module_template.{$filename}.{$modelDir}"
                );
            }
        }
    }
}
