<?php

namespace Modules\DesaModuleTemplate\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

abstract class BaseMigration extends Migration
{
    /**
     * Get the connection name for the migration.
     */
    public function getConnection(): ?string
    {
        return env('DESA_MODULE_TEMPLATE_DB_CONNECTION')
            ?? config('desa_module_template.database.database_connection')
            ?? 'desa_module_template';
    }

    /**
     * Get the schema builder for the migration.
     */
    protected function schema()
    {
        return Schema::connection($this->getConnection());
    }
}