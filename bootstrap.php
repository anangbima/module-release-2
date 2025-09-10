<?php

require_once __DIR__ . '/helpers/module_setup.php';

$currentNamespace = detectCurrentNamespace(__DIR__);

if (!$currentNamespace) {
    exitWith("❌ Failed to detect module namespace.\n");
}

spl_autoload_register(function ($class) use ($currentNamespace) {
    $prefix = "Modules\\$currentNamespace\\";
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

foreach (glob(__DIR__ . '/src/Console/Commands/*.php') as $file) {
    require_once $file;
}
