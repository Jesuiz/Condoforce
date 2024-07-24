<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->string('area');
            $table->string('status')->default('Asignado');
            $table->integer('time_limit')->nullable()->default(null);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('condominium_id');
            $table->foreign('condominium_id')->references('id')->on('condominiums')->onDelete('cascade');
            $table->unsignedBigInteger('report_id')->nullable()->default(null);
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
