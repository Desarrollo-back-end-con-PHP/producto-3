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
        Schema::create('transfer_precios', function (Blueprint $table) {
            $table->id('id_precios');

            // FK Vehiculos
            $table->unsignedBigInteger('id_vehiculo');
            $table->foreign('id_vehiculo')->references('id_vehiculo')->on('transfer_vehiculos');

            // FK Hotel 
            $table->unsignedBigInteger('id_hotel');
            $table->foreign('id_hotel')->references('id_hotel')->on('transfer_hotels');

            $table->integer('Precio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_precios');
    }
};
