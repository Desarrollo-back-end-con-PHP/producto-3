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
        Schema::create('transfer_viajeros', function (Blueprint $table) {
            $table->id('id_viajero');

            $table->string('nombre', 100);
            $table->string('apellido1', 100);
            $table->string('apellido2', 100);
            $table->string('direccion', 100);
            $table->string('codigoPostal', 100);
            $table->string('ciudad', 100);
            $table->string('pais', 100);

            $table->string('email', 100)->unique();
            $table->string('password', 100);

            $table->enum('status', ['activo', 'inactivo'])->default('activo');

            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_viajeros');
    }
};
