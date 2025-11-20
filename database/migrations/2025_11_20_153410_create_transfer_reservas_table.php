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
        Schema::create('transfer_reservas', function (Blueprint $table) {
            $table->id('id_reserva');

            $table->string('localizador', 100);

            //FK Hotel
            $table->unsignedBigInteger('id_hotel')->nullable();
            $table->foreign('id_hotel')->references('id_hotel')->on('transfer_hotels');
            //FK Tipo reserva
            $table->unsignedBigInteger('id_tipo_reserva');
            $table->foreign('id_tipo_reserva')->references('id_tipo_reserva')->on('transfer_tipo_reservas');
            //FK Viajero
            $table->string('email_cliente', 100);
            $table->foreign('email_cliente')->references('email')->on('transfer_viajeros');
            //FK Destino
            $table->unsignedBigInteger('id_destino');
            $table->foreign('id_destino')->references('id_hotel')->on('transfer_hotels');
            // FK: Vehículo
            $table->unsignedBigInteger('id_vehiculo');
            $table->foreign('id_vehiculo')->references('id_vehiculo')->on('transfer_vehiculos');

            $table->dateTime('fecha_reserva');
            $table->dateTime('fecha_modificacion');
            $table->date('fecha_entrada')->nullable();
            $table->time('hora_entrada')->nullable();
            $table->string('numero_vuelo_entrada', 50)->nullable();
            $table->string('origen_vuelo_entrada', 50)->nullable();
            $table->time('hora_vuelo_salida')->nullable();
            $table->date('fecha_vuelo_salida')->nullable();

            $table->string('numero_vuelo_salida', 20)->nullable();
            $table->time('hora_recogida')->nullable();
            $table->integer('num_viajeros');
            $table->enum('status', ['pendiente', 'confirmada', 'cancelada', 'completada'])
                ->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_reservas');
    }
};
