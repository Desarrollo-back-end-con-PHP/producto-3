@extends('layouts.hotel')

@section('title', 'Nueva Reserva - Panel Hotel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex align-items-center justify-content-between mb-4 mt-3">
                <h2 class="h4 fw-bold text-dark mb-0">
                    <i class="fas fa-concierge-bell me-2 text-warning"></i>Nueva Reserva
                </h2>
                <a href="{{ route('hotel.panel') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>

            <div class="card shadow border-0 border-top border-warning border-4 mb-5">
                <div class="card-body p-5">

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hotel.reservas.store') }}" method="POST">
                        @csrf

                        {{-- SECCIÓN 1: DATOS DEL CLIENTE --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">1. Datos del Huésped</h6>
                        <div class="row g-3 mb-5">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email_cliente" class="form-control" 
                                           value="{{ old('email_cliente') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Pasajeros <span class="text-danger">*</span></label>
                                <input type="number" name="num_viajeros" class="form-control" 
                                       value="{{ old('num_viajeros', 1) }}" min="1" max="50" required>
                            </div>
                        </div>

                        {{-- SECCIÓN 2: DETALLES --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">2. Trayecto y Vehículo</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Trayecto <span class="text-danger">*</span></label>
                                <select name="id_tipo_reserva" class="form-select bg-light" required>
                                    @foreach($tipos as $t)
                                        <option value="{{ $t->id_tipo_reserva }}" {{ old('id_tipo_reserva') == $t->id_tipo_reserva ? 'selected' : '' }}>
                                            {{ $t->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text small">Indica si es llegada o salida.</div>
                            </div>

                            {{-- CAMBIO AQUÍ: Hotel Fijo --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ubicación del Hotel</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt text-danger"></i></span>
                                    {{-- Mostramos el nombre del hotel logueado y deshabilitamos el campo --}}
                                    <input type="text" class="form-control bg-light border-0 fw-bold text-dark" 
                                           value="{{ Auth::guard('hotel')->user()->usuario }}" disabled readonly>
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN VEHÍCULOS --}}
                        <div class="mb-5">
                            <label class="form-label fw-bold mb-3">Selecciona un Vehículo <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach($vehiculos as $v)
                                <div class="col-md-4">
                                    <label class="card h-100 border shadow-sm p-3 text-center btn-vehicle-option position-relative">
                                        <input type="radio" name="id_vehiculo" value="{{ $v->id_vehiculo }}" class="form-check-input position-absolute top-0 start-0 m-3" 
                                            {{ old('id_vehiculo') == $v->id_vehiculo ? 'checked' : '' }} required>
                                        
                                        <div class="pt-2">
                                            <i class="fas fa-car fa-2x text-secondary mb-2"></i>
                                            <div class="fw-bold text-dark">{{ $v->modelo ?? $v->descripcion ?? 'Estándar' }}</div>
                                            <div class="badge bg-light text-dark border mt-1">{{ $v->plazas }} Plazas</div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- SECCIÓN 3: FECHA --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">3. Fecha del Servicio</h6>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_entrada" class="form-control" value="{{ old('fecha_entrada') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hora <span class="text-danger">*</span></label>
                                <input type="time" name="hora_entrada" class="form-control" value="{{ old('hora_entrada') }}" required>
                            </div>
                        </div>

                        {{-- SECCIÓN 4: VUELO --}}
                        <div class="bg-light p-4 rounded mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-plane me-2"></i>Datos de Vuelo (Opcional)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small">Nº Vuelo</label>
                                    <input type="text" name="numero_vuelo_entrada" class="form-control bg-white" placeholder="Ej: IB3402">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Origen</label>
                                    <input type="text" name="origen_vuelo_entrada" class="form-control bg-white" placeholder="Ej: Madrid">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark">Confirmar Reserva</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-vehicle-option { cursor: pointer; transition: all 0.2s; }
    .btn-vehicle-option:hover { background-color: #fff3cd; border-color: #ffc107 !important; }
    input[type="radio"]:checked + div { color: #000; }
    label:has(input[type="radio"]:checked) {
        border-color: #ffc107 !important;
        background-color: #fff3cd;
        border-width: 2px !important;
    }
</style>
@endsection