<?php

namespace Modules\ModuleRelease2\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ModuleRelease2\Models\User;
use Modules\ModuleRelease2\Services\Shared\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(PermissionRegistrar $registrar)
    {
        // Ambil model Role module template
        $roleModel = $registrar->getRoleClass();

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'slug' => 'admin-user',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Ambil role admin module template
        $adminRole = $roleModel::where('name', 'admin')->first();
        if ($adminRole) {
            
            $admin->roles()->syncWithoutDetaching([
                $adminRole->id => ['model_type' => User::class]
            ]);
        }

        // Buat dummy users
        User::factory()->count(10)->create()->each(function ($user) use ($roleModel) {
            $userRole = $roleModel::where('name', 'user')->first();
            if ($userRole) {
                $user->roles()->syncWithoutDetaching([
                    $userRole->id => ['model_type' => User::class]
                ]);
            }
        });
    }
}
