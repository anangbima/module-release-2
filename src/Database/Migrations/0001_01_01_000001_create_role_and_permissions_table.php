<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\DesaModuleTemplate\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    /**
     * Get full table name with prefix.
     */
    protected function table(string $base): string
    {
        return 'desa_module_template_' . $base;
    }

    public function up(): void
    {
        $this->schema()->create($this->table('permissions'), function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('guard_name');
            $table->string('module_name')->nullable();
            $table->timestamps();
        });

        $this->schema()->create($this->table('roles'), function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        $this->schema()->create($this->table('model_has_permissions'), function (Blueprint $table) {
            $table->foreignUlid('permission_id');
            $table->string('model_type');
            $table->ulid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($this->table('permissions'))
                ->onDelete('cascade');
        });

        $this->schema()->create($this->table('model_has_roles'), function (Blueprint $table) {
            $table->foreignUlid('role_id');
            $table->string('model_type');
            $table->ulid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($this->table('roles'))
                ->onDelete('cascade');
        });

        $this->schema()->create($this->table('role_has_permissions'), function (Blueprint $table) {
            $table->foreignUlid('permission_id');
            $table->foreignUlid('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($this->table('permissions'))
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($this->table('roles'))
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        $this->schema()->dropIfExists($this->table('role_has_permissions'));
        $this->schema()->dropIfExists($this->table('model_has_roles'));
        $this->schema()->dropIfExists($this->table('model_has_permissions'));
        $this->schema()->dropIfExists($this->table('roles'));
        $this->schema()->dropIfExists($this->table('permissions'));
    }
};
