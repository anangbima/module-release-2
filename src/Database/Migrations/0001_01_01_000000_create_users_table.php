<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\DesaModuleTemplate\Database\Migrations\BaseMigration;

return new class extends BaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->schema()->create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('last_activity')->nullable();

            $table->string('province_code')->nullable();
            $table->string('city_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('village_code')->nullable();

            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->schema()->create('desa_module_template_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        $this->schema()->create('desa_module_template_sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUlid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema()->dropIfExists('desa_module_template_sessions');
        $this->schema()->dropIfExists('desa_module_template_password_reset_tokens');
        $this->schema()->dropIfExists('users');
    }
};