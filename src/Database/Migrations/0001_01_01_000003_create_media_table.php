<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\ModuleRelease2\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    public function up(): void
    {
        $this->schema()->create('media', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->nullableUlidMorphs('model');
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('disk')->nullable();
            $table->string('collection')->nullable();
            $table->string('usage')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema()->dropIfExists( 'media');
    }
};
