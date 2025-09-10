<?php

require_once __DIR__ . '/helpers/module_setup.php';

echo "\n======================================================\n";
echo "                    Setup Module\n";
echo "======================================================\n\n";

echo "This script is intended for module installation.\n\n";

// Check if the script already ran
if (file_exists(__DIR__ . '/.setup.lock')) {
    exitWith("❌ Module has already been set up. Remove `.setup.lock` to re-run.\n\n");
}

/**
 * 
 * input module name
 * 
 */
$rawName = ask("Please enter the module name (ex: Surat Desa, Perpus Sekolah):");
$moduleMeta = generateModuleMeta($rawName);


/**
 * 
 * Variable Definitions
 * 
 */
$currentNamespace = detectCurrentNamespace(__DIR__);

if (!$currentNamespace) {
    exitWith("❌ Failed to detect module namespace.\n");
}
$currentRawName = splitStudlyCase($currentNamespace);
$currentModuleMeta = generateModuleMeta($currentRawName);

// echo "\nDetected current raw name: {$currentRawName}\n";
// echo "Detected current module namespace: {$currentModuleMeta['label']}\n";





/**
 * 
 * Main process
 * 
 */
echo "\n🔁 Replacing content in files...\n";

// echo "This will replace all occurrences of the current module name with the new one.\n";
// echo "Constant {$currentModuleMeta['constant']}\n to -> Constant {$moduleMeta['constant']}\n";
// echo "Module name in views: <x-{$currentModuleMeta['kebab']}:: to  <x-{$moduleMeta['kebab']}::\n\n";


replaceInFiles(__DIR__, [
    // Namespace in general
    "Modules\\{$currentModuleMeta['studly']}\\" => "Modules\\{$moduleMeta['studly']}\\",
    "Modules\\{$currentModuleMeta['studly']}" => "Modules\\{$moduleMeta['studly']}",

    // All meta keys
    "{$currentModuleMeta['label']}" => "{$moduleMeta['label']}",
    "{$currentModuleMeta['studly']}" => "{$moduleMeta['studly']}",
    "{$currentModuleMeta['kebab']}" => "{$moduleMeta['kebab']}",
    "{$currentModuleMeta['snake']}" => "{$moduleMeta['snake']}",
    "{$currentModuleMeta['plain']}" => "{$moduleMeta['plain']}",
    "{$currentModuleMeta['constant']}" => "{$moduleMeta['constant']}",

    // signature 
    "module:{$currentModuleMeta['plain']}:" => "module:{$moduleMeta['plain']}",

    "{$currentModuleMeta['snake']}_" => "{$moduleMeta['snake']}_",

    // Module name in views
    "<x-{$currentModuleMeta['kebab']}::"         => "<x-{$moduleMeta['kebab']}::",

    // Constants
    "{$currentModuleMeta['constant']}_" => "{$moduleMeta['constant']}_",
]);

// Update composer.json
echo "🔄 Updating composer.json...\n";
updateComposerJson(__DIR__ . '/composer.json', [
    'name' => "modules/{$moduleMeta['kebab']}",
    'description' => "Modul {$moduleMeta['label']} untuk manajemen fitur terkait {$moduleMeta['label']} di sistem desa digital.",
    'autoload.psr-4' => [
        "Modules\\{$moduleMeta['studly']}\\" => "src/"
    ],
    'extra.laravel.providers' => [
        "Modules\\{$moduleMeta['studly']}\\Providers\\ModuleServiceProvider"
    ]
]);

// Create the .setup.lock file to indicate setup completion
file_put_contents(__DIR__ . '/.setup.lock', 'setup completed');

echo "✅ Module setup completed successfully!\n\n";




/**
 * 
 * Helper
 * 
 */

// ask method
function ask(string $prompt): string
{
    echo "$prompt ";
    $input = trim(fgets(STDIN));

    if (empty($input)) {
        exitWith("❌ Module name cannot be empty.\n");
    }

    return $input;
}

// replaceInFiles method
function replaceInFiles(string $directory, array $replacements): void
{
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    foreach ($files as $file) {
        if ($file->isDir()) continue;
        if (!in_array($file->getExtension(), ['php', 'json', 'js', 'stub'])) continue;

        $contents = file_get_contents($file->getPathname());
        foreach ($replacements as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }
        file_put_contents($file->getPathname(), $contents);
    }
}

// updateComposerJson method
function updateComposerJson(string $file, array $replacements): void
{
    $composer = json_decode(file_get_contents($file), true);

    if (isset($replacements['name'])) {
        $composer['name'] = $replacements['name'];
    }

    if (isset($replacements['description'])) {
        $composer['description'] = $replacements['description'];
    }

    if (isset($replacements['autoload.psr-4'])) {
        $composer['autoload']['psr-4'] = $replacements['autoload.psr-4'];
    }

    if (isset($replacements['extra.laravel.providers'])) {
        $composer['extra']['laravel']['providers'] = $replacements['extra.laravel.providers'];
    }

    file_put_contents($file, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}