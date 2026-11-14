<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->decimal('tarifa_hora', 8, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_vehiculo');
    }
};
