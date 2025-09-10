<?php

namespace Modules\ModuleRelease2\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ModuleRelease2\Models\LogActivity;

class LogActivitySeeder extends Seeder
{
    public function run(): void
    {
        LogActivity::factory()->count(10)->create();
    }
}
