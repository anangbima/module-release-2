<?php

namespace Modules\DesaModuleTemplate\Providers\Concerns;

use Modules\DesaModuleTemplate\Console\Commands\AddEnvCommand;
use Modules\DesaModuleTemplate\Console\Commands\InstallCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeComponentCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeControllerCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeExporterCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeFactoryCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeImporterCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeMiddlewareCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeModelCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeNotificationCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeRepositoryCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeRepositoryInterfaceCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeRequestCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeResourceCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeSeederCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeServiceCommand;
use Modules\DesaModuleTemplate\Console\Commands\MakeTraitCommand;
use Modules\DesaModuleTemplate\Console\Commands\MigrateCommand;
use Modules\DesaModuleTemplate\Console\Commands\RegisterProviderCommand;
use Modules\DesaModuleTemplate\Console\Commands\RouteListCommand;
use Modules\DesaModuleTemplate\Console\Commands\UpdateAutoloadCommand;

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
