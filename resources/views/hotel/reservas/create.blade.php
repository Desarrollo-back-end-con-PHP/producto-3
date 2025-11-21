@extends('layouts.app')

@section('title', 'Nueva Reserva Corporativa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i> Nueva Reserva Corporativa</h4>
                </div>
                <div class="card-body p-4">

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('hotel.reservas.store') }}" method="POST">
                        @csrf

                        <h5 class="mb-3 text-secondary">Datos del Cliente</h5>
                        <div class="mb-3">
                            <label class="form-label">Email del Cliente (Viajero)</label>
                            <input type="email" name="email_cliente" class="form-control" required placeholder="cliente@email.com">
                            <div class="form-text">El cliente debe estar registrado en nuestra base de datos de viajeros.</div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3 text-secondary">Detalles del Trayecto</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Reserva</label>
                                <select name="id_tipo_reserva" class="form-select" required>
                                    @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id_tipo_reserva }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hotel Destino / Origen</label>
                                <select name="id_destino" class="form-select" required>
                                    @foreach($hotelesDestino as $destino)
                                    <option value="{{ $destino->id_hotel }}">{{ $destino->usuario }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vehículo</label>
                                <select name="id_vehiculo" class="form-select" required>
                                    @foreach($vehiculos as $v)
                                    <option value="{{ $v->id_vehiculo }}">{{ $v->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número de Viajeros</label>
                                <input type="number" name="num_viajeros" class="form-control" min="1" max="50" value="1" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3 text-secondary">Fechas y Vuelos</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha Entrada</label>
                                <input type="date" name="fecha_entrada" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Entrada</label>
                                <input type="time" name="hora_entrada" class="form-control" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Núm. Vuelo (Opcional)</label>
                                <input type="text" name="numero_vuelo_entrada" class="form-control" placeholder="IB1234">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Origen Vuelo (Opcional)</label>
                                <input type="text" name="origen_vuelo_entrada" class="form-control" placeholder="Madrid">
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Confirmar Reserva y Calcular Comisión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection