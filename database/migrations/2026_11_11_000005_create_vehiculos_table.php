<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 15)->unique();
            $table->foreignId('id_cliente')->nullable()->constrained('clientes');
            $table->foreignId('id_tipo_vehiculo')->constrained('tipos_vehiculo');
            $table->string('marca', 50)->nullable();
            $table->string('modelo', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
