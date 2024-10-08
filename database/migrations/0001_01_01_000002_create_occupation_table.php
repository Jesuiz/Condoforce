<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occupation', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Residente', 'Delegado', 'Vigilante', 'Supervisor', 'Mantenimiento', 'Administrador', 'Gerente']);
            $table->decimal('salary', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occupation');
    }
};