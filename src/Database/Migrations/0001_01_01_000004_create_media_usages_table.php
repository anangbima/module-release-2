<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\DesaModuleTemplate\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    public function up(): void
    {
        $this->schema()->create('media_usages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('media_id');
            $table->string('model_type');
            $table->ulid('model_id');
            $table->string('usage')->nullable();
            $table->foreign('media_id')->references('id')->on('media')->cascadeOnDelete();
            $table->index(['model_type', 'model_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema()->dropIfExists('media_usages');
    }
};
