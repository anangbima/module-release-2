<?php 

namespace Modules\ModuleRelease2\Providers\Concerns;

use DesaDigitalSupport\NotificationProvider\Services\NotificationAggregator;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Modules\ModuleRelease2\Channels\ModuleDatabaseChannel;
use Modules\ModuleRelease2\Services\Shared\NotificationService;

trait ConfigureNotifications
{
    public function configureNotifications(): void
    {
        // Register custom channel
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('module_release_2_database', function ($app) {
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
