<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\ModuleRelease2\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    public function up(): void
    {
        $this->schema()->create('log_activities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('user_id')->nullable();
            $table->timestamp('logged_at')->useCurrent();
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->string('model_id')->nullable();
            $table->text('description')->nullable();
            $table->json('data_before')->nullable();
            $table->json('data_after')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema()->dropIfExists('log_activities');
    }
};
