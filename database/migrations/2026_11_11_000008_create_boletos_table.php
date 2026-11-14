<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vehiculo')->constrained('vehiculos');
            $table->foreignId('id_espacio_estacionamiento')->constrained('espacios_estacionamiento');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->dateTime('hora_entrada');
            $table->dateTime('hora_salida')->nullable();
            $table->unsignedInteger('total_tiempo_minutos')->nullable();
            $table->foreignId('id_metodo_pago')->nullable()->constrained('metodos_pago');
            $table->decimal('monto_total', 8, 2)->nullable();
            $table->boolean('esta_pagado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletos');
    }
};
