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
        Schema::create('transfer_hotels', function (Blueprint $table) {
            $table->id('id_hotel');

            //asignar id_zona como FK
            $table->unsignedBigInteger('id_zona')->nullable();
            $table->foreign('id_zona')->references('id_zona')->on('transfer_zonas');

            $table->integer('Comision')->nullable();
            $table->string('usuario', 100)->nullable();
            $table->string('password', 100);

            $table->enum('status', ['activo', 'inactivo'])->default('activo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_hotels');
    }
};
