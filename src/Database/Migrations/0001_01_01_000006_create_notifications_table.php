<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\DesaModuleTemplate\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    public function up(): void
    {
        $this->schema()->create('notifications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('type');
            $table->ulid('notifiable_id');
            $table->string('notifiable_type');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema()->dropIfExists('notifications');
    }
};
