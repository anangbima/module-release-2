<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\ModuleRelease2\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    public function up(): void
    {
        $this->schema()->create('settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('group')->nullable();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema()->dropIfExists('settings');
    }
};
