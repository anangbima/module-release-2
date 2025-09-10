<?php

namespace Modules\ModuleRelease2\Providers\Concerns;

use Modules\ModuleRelease2\Console\Commands\AddEnvCommand;
use Modules\ModuleRelease2\Console\Commands\InstallCommand;
use Modules\ModuleRelease2\Console\Commands\MakeComponentCommand;
use Modules\ModuleRelease2\Console\Commands\MakeControllerCommand;
use Modules\ModuleRelease2\Console\Commands\MakeExporterCommand;
use Modules\ModuleRelease2\Console\Commands\MakeFactoryCommand;
use Modules\ModuleRelease2\Console\Commands\MakeImporterCommand;
use Modules\ModuleRelease2\Console\Commands\MakeMiddlewareCommand;
use Modules\ModuleRelease2\Console\Commands\MakeModelCommand;
use Modules\ModuleRelease2\Console\Commands\MakeNotificationCommand;
use Modules\ModuleRelease2\Console\Commands\MakeRepositoryCommand;
use Modules\ModuleRelease2\Console\Commands\MakeRepositoryInterfaceCommand;
use Modules\ModuleRelease2\Console\Commands\MakeRequestCommand;
use Modules\ModuleRelease2\Console\Commands\MakeResourceCommand;
use Modules\ModuleRelease2\Console\Commands\MakeSeederCommand;
use Modules\ModuleRelease2\Console\Commands\MakeServiceCommand;
use Modules\ModuleRelease2\Console\Commands\MakeTraitCommand;
use Modules\ModuleRelease2\Console\Commands\MigrateCommand;
use Modules\ModuleRelease2\Console\Commands\RegisterProviderCommand;
use Modules\ModuleRelease2\Console\Commands\RouteListCommand;
use Modules\ModuleRelease2\Console\Commands\UpdateAutoloadCommand;

trait RegisterCommands
{
    protected function registerModuleCommands(): void
    {
        $this->commands([
            AddEnvCommand::class,
            InstallCommand::class,
            MigrateCommand::class,
            RegisterProviderCommand::class,
            UpdateAutoloadCommand::class,
            MakeControllerCommand::class,
            MakeModelCommand::class,
            MakeRequestCommand::class,
            MakeServiceCommand::class,
            MakeRepositoryCommand::class,
            MakeRepositoryInterfaceCommand::class,
            MakeSeederCommand::class,
            MakeFactoryCommand::class,
            MakeResourceCommand::class,
            MakeMiddlewareCommand::class,
            MakeNotificationCommand::class,
            RouteListCommand::class,
            MakeComponentCommand::class,
            MakeTraitCommand::class,
            MakeExporterCommand::class,
            MakeImporterCommand::class,
        ]);
    }
}
