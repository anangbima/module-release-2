<?php

namespace Modules\DesaModuleTemplate\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DesaModuleTemplate\Models\LogActivity;

class LogActivitySeeder extends Seeder
{
    public function run(): void
    {
        LogActivity::factory()->count(10)->create();
    }
}
