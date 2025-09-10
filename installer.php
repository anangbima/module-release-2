<?php

define('LARAVEL_START', microtime(true));

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/helpers/module_setup.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';

use Illuminate\Console\Application as Artisan;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$currentNamespace = detectCurrentNamespace(__DIR__);

if (!$currentNamespace) {
    exitWith("❌ Failed to detect module namespace.\n");
}

// Register manual command jika perlu (opsional kalau sudah via service provider)
Artisan::starting(function ($artisan) use ($currentNamespace) {
    foreach (glob(__DIR__ . '/src/Console/Commands/*.php') as $file) {
        require_once $file;
        $class = "Modules\\$currentNamespace\\Console\\Commands\\" . basename($file, '.php');
        if (class_exists($class)) {
            $artisan->resolve($class);
        }
    }
});

// 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Jalankan artisan command dengan full CLI input & output (support warna)
$input = new ArgvInput(); // Tangkap argument dari CLI langsung
$commandName = 'module:' . strtolower($currentNamespace) . ':install';

$options = [];
foreach (['--fresh', '--refresh', '--seed'] as $option) {
    if ($input->hasParameterOption($option)) {
        $options['--' . ltrim($option, '-')] = true;
    }
}

$output = new ConsoleOutput(); // Output dengan warna

$status = $kernel->handle($input, $output);
exit($status);

