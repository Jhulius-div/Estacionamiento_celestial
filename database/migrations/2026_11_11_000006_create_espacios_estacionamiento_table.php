<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('espacios_estacionamiento', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('piso');
            $table->string('sector', 10);
            $table->unsignedSmallInteger('numero');
            $table->boolean('esta_ocupado')->default(false);
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->unique(['piso', 'sector', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('espacios_estacionamiento');
    }
};
