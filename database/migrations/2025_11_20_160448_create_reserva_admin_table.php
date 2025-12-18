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
        Schema::create('reserva_admin', function (Blueprint $table) {
            $table->id();

            // FK Reserva 
            $table->unsignedBigInteger('id_reserva');
            $table->foreign('id_reserva')->references('id_reserva')->on('transfer_reservas')->onDelete('cascade');

            // ID Admin 
            $table->integer('id_admin');

            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_admin');
    }
};
