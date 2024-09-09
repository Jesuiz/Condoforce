<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('country', 2);
            $table->enum('doc_type', ['DNI', 'CE', 'PTP', 'PAS']);
            $table->string('document')->unique()->nullable();
            $table->unsignedBigInteger('cellphone')->nullable();
            $table->mediumText('address')->nullable();
            $table->string('profile_img')->nullable();

            $table->unsignedBigInteger('condominium_id');
            $table->foreign('condominium_id')->references('id')->on('condominiums')->onDelete('cascade');
            
            $table->unsignedBigInteger('occupation_id');
            $table->foreign('occupation_id')->references('id')->on('occupation')->onDelete('cascade');

            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
