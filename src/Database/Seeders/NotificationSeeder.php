<?php

namespace Modules\ModuleRelease2\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\ModuleRelease2\Models\Notification;
use Modules\ModuleRelease2\Models\User;
use Modules\ModuleRelease2\Services\Shared\PermissionRegistrar;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        Notification::factory()
            ->count(10)
            ->create([
                'notifiable_id' => User::inRandomOrder()->first()?->id,
                'notifiable_type' => User::class,
                'created_at' => fn() => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => fn() => Carbon::now()->subDays(rand(0, 30)),
            ]);

        $registrar = app(PermissionRegistrar::class);
        $roleModel = $registrar->getRoleClass();
        $adminRole = $roleModel::where('name', 'admin')->first();

        $admins = User::whereHas('roles', function ($q) use ($adminRole) {
            $q->where('role_id', $adminRole->id);
        })->get();

        foreach ($admins as $admin) {
            // 5 notifikasi hari ini
            Notification::factory()
                ->count(5)
                ->create([
                    'notifiable_id' => $admin->id,
                    'notifiable_type' => User::class,
                    'created_at' => fn() => Carbon::now()->subMinutes(rand(0, 720)), // dalam 12 jam terakhir
                    'updated_at' => Carbon::now(),
                ]);

            // 3 notifikasi kemarin
            Notification::factory()
                ->count(3)
                ->create([
                    'notifiable_id' => $admin->id,
                    'notifiable_type' => User::class,
                    'created_at' => fn() => Carbon::yesterday()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'updated_at' => fn() => Carbon::yesterday()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                ]);

            // 12 notifikasi random
            Notification::factory()
                ->count(12)
                ->create([
                    'notifiable_id' => $admin->id,
                    'notifiable_type' => User::class,
                    'created_at' => fn() => Carbon::now()->subDays(rand(0, 60))->addMinutes(rand(0, 1440)),
                    'updated_at' => fn() => Carbon::now()->subDays(rand(0, 60))->addMinutes(rand(0, 1440)),
                ]);
        }
    }
}
