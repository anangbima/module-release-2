<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\DesaModuleTemplate\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    public function up(): void
    {
        $this->schema()->create('api_clients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('api_key')->unique();
            $table->string('secret_key');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema()->dropIfExists('api_clients');
    }
};
