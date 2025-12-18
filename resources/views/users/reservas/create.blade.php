@extends('layouts.app')

@section('title', 'Nueva Reserva')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-suitcase me-2"></i>Nueva Reserva Particular</h4>
                </div>
                <div class="card-body p-4">

                    {{-- Mensajes de error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reservas.store') }}" method="POST">
                        @csrf

                        <h5 class="text-secondary mb-3">Detalles del Viaje</h5>

                        <div class="row g-3 mb-4">
                            {{-- Tipo de Trayecto --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Trayecto</label>
                                <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select" required>
                                    @foreach($tipos as $t)
                                        <option value="{{ $t->id_tipo_reserva }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Destino --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hotel / Destino</label>
                                <select name="id_destino" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($hotelesDestino as $h)
                                        <option value="{{ $h->id_hotel }}">{{ $h->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Vehículo --}}
                            <div class="col-md-6">
                                <label class="form-label">Vehículo</label>
                                <select name="id_vehiculo" class="form-select" required>
                                    @foreach($vehiculos as $v)
                                        <option value="{{ $v->id_vehiculo }}">{{ $v->modelo ?? $v->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Viajeros --}}
                            <div class="col-md-6">
                                <label class="form-label">Pasajeros</label>
                                <input type="number" name="num_viajeros" class="form-control" value="1" min="1" required>
                            </div>
                        </div>

                        <hr>

                        {{-- Campos Genéricos de Fecha (Simplificado para el ejemplo) --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha_entrada" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora</label>
                                <input type="time" name="hora_entrada" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número de Vuelo (Opcional)</label>
                                <input type="text" name="numero_vuelo_entrada" class="form-control" placeholder="Ej: IB-1234">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Origen del Vuelo (Opcional)</label>
                                <input type="text" name="origen_vuelo_entrada" class="form-control" placeholder="Ej: Madrid">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Confirmar Reserva</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
