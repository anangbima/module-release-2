<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class RouteListCommand extends Command
{
    protected $signature;
    protected $description = 'List all routes for Module Release 2 module';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:route-list';
        parent::__construct();
    }

    public function handle()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return str_contains($route->getName(), 'module-release-2.')
                || str_contains($route->uri(), 'module-release-2');
        });

        if ($routes->isEmpty()) {
            $this->warn('No routes found for module: testmodule4');
            return self::SUCCESS;
        }

        $this->addCustomStyles();

        // Header
        $this->line('');
        $this->line("<fg=white;options=bold>+---------------------+------------------------------+---------------------------+</>");
        $this->line("<fg=white;options=bold>| METHOD              | URI                          | NAME                      |</>");
        $this->line("<fg=white;options=bold>+---------------------+------------------------------+---------------------------+</>");

        foreach ($routes as $route) {
            $method = $this->formatMethod($route->methods());
            $uri = $this->truncate($route->uri(), 30);
            $name = $this->truncate($route->getName(), 25);

            $this->line(sprintf(
                "| %-19s | %-28s | %-25s |",
                $method,
                $uri,
                $name
            ));
        }

        $this->line("<fg=white;options=bold>+---------------------+------------------------------+---------------------------+</>");
        $this->line('');

        return self::SUCCESS;
    }

    protected function truncate(?string $string, int $limit): string
    {
        if (!$string) return '-';
        return strlen($string) > $limit ? substr($string, 0, $limit - 3) . '...' : $string;
    }

    protected function formatMethod(array $methods): string
    {
        $method = implode('|', $methods);

        return match (true) {
            str_contains($method, 'GET')    => "<fg=green>$method</>",
            str_contains($method, 'POST')   => "<fg=blue>$method</>",
            str_contains($method, 'PUT')    => "<fg=yellow>$method</>",
            str_contains($method, 'DELETE') => "<fg=red>$method</>",
            default                         => "<fg=default>$method</>",
        };
    }

    protected function addCustomStyles(): void
    {
        if (!$this->output->getFormatter()->hasStyle('bold')) {
            $this->output->getFormatter()->setStyle('bold', new OutputFormatterStyle('white', null, ['bold']));
        }
    }
}
