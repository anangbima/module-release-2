<?php

if (!function_exists('detectModuleName')) {
    function detectCurrentNamespace(string $directory): ?string
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());

                if (preg_match('/namespace\s+Modules\\\\([A-Za-z0-9_]+)\\\\?/', $content, $matches)) {
                    return $matches[1];
                }
            }
        }

        return null;
    }
}

// kebabCase method
if (!function_exists('kebabCase')) {
    function kebabCase(string $rawName): string
    {
        $kebab = preg_replace('/([a-z])([A-Z0-9])/', '$1-$2', $rawName);
        $kebab = preg_replace('/([0-9])([a-zA-Z])/', '$1-$2', $kebab);
        return strtolower($kebab);
    }
}

// exitWith method
if (!function_exists('exitWith')) {
    function exitWith(string $message): void
    {
        echo "\n$message\n";
        exit(1);
    }
}

// seperator string
if (!function_exists('splitStudlyCase')) {
    function splitStudlyCase(string $studly): string
    {
        return trim(preg_replace('/(?<!^)(?=[A-Z0-9])/', ' ', $studly));
    }
}


// generate module meta
if (!function_exists('generateModuleMeta')) {
    function generateModuleMeta(string $rawName): array
    {
        $cleanName = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $rawName));
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);

        return [
            'label'         => $cleanName, // "Desa Module Template"
            'studly'        => str_replace(' ', '', ucwords(strtolower($cleanName))), // "Desa Module Template"
            'kebab'         => strtolower(str_replace(' ', '-', $cleanName)), // "desa-module-template"
            'snake'         => strtolower(str_replace(' ', '_', $cleanName)), // "desa_module_template"
            'plain'         => strtolower(str_replace(' ', '', $cleanName)), // "desamoduletemplate"
            'constant'      => strtoupper(str_replace(' ', '_', $cleanName)), // "DESA_MODULE_TEMPLATE"
        ];
    }
}