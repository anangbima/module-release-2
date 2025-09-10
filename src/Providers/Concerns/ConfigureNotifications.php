<?php 

namespace Modules\DesaModuleTemplate\Providers\Concerns;

use DesaDigitalSupport\NotificationProvider\Services\NotificationAggregator;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Modules\DesaModuleTemplate\Channels\ModuleDatabaseChannel;
use Modules\DesaModuleTemplate\Services\Shared\NotificationService;

trait ConfigureNotifications
{
    public function configureNotifications(): void
    {
        // Register custom channel
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('desa_module_template_database', function ($app) {
                return new ModuleDatabaseChannel;
            });
        });

        // Register module's notification provider into aggregator
        $this->app->booted(function () {
            if ($this->app->bound(NotificationAggregator::class)) {
                $aggregator = $this->app->make(NotificationAggregator::class);
                $provider   = $this->app->make(NotificationService::class);
                $aggregator->registerProvider($provider);
            }
        });
    }
}
