@extends('layouts.hotel')

@section('title', 'Editar Reserva - Panel Hotel')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="h4 fw-bold text-dark mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>Editar Reserva: <span class="font-monospace text-muted">{{ $reserva->localizador }}</span>
                </h2>
                <a href="{{ route('hotel.panel') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>

            <div class="card shadow border-0 border-top border-primary border-4">
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

                    <form action="{{ route('hotel.reservas.update', $reserva->id_reserva) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- SECCIÓN 1: DATOS BÁSICOS --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Cliente</label>
                                <input type="email" name="email_cliente" class="form-control" 
                                       value="{{ old('email_cliente', $reserva->email_cliente) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nº Pasajeros</label>
                                <input type="number" name="num_viajeros" class="form-control" 
                                       value="{{ old('num_viajeros', $reserva->num_viajeros) }}" min="1" required>
                            </div>
                        </div>

                        {{-- SECCIÓN 2: TRAYECTO --}}
                        <div class="row g-3 mb-4 p-3 bg-light rounded">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Trayecto</label>
                                <select name="id_tipo_reserva" class="form-select bg-white" required>
                                    @foreach($tipos as $t)
                                        <option value="{{ $t->id_tipo_reserva }}" 
                                            {{ $reserva->id_tipo_reserva == $t->id_tipo_reserva ? 'selected' : '' }}>
                                            {{ $t->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- CAMBIO AQUÍ: Hotel fijo al usuario logueado --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Ubicación (Hotel)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-primary"><i class="fas fa-map-pin"></i></span>
                                    {{-- Campo visual bloqueado --}}
                                    <input type="text" class="form-control bg-white fw-bold text-dark" 
                                           value="{{ Auth::guard('hotel')->user()->usuario }}" disabled readonly>
                                </div>
                                {{-- Campo oculto para enviar el ID --}}
                                <input type="hidden" name="id_destino" value="{{ Auth::guard('hotel')->user()->id_hotel }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Vehículo</label>
                                <select name="id_vehiculo" class="form-select bg-white" required>
                                    <option value="" disabled>Seleccionar vehículo...</option>
                                    @foreach($vehiculos as $v)
                                        <option value="{{ $v->id_vehiculo }}" 
                                            {{ $reserva->id_vehiculo == $v->id_vehiculo ? 'selected' : '' }}>
                                            {{ $v->modelo ?? $v->descripcion ?? 'Vehículo Estándar' }} 
                                            {{ isset($v->plazas) ? '('.$v->plazas.' pax)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- SECCIÓN 3: FECHA --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha Servicio</label>
                                <input type="date" name="fecha_entrada" class="form-control" 
                                       value="{{ old('fecha_entrada', $reserva->fecha_entrada->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Hora Servicio</label>
                                <input type="time" name="hora_entrada" class="form-control" 
                                       value="{{ old('hora_entrada', \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i')) }}" required>
                            </div>
                        </div>

                        {{-- SECCIÓN 4: VUELO --}}
                        <div class="row g-3 mb-4 border-top pt-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Nº Vuelo (Opcional)</label>
                                <input type="text" name="numero_vuelo_entrada" class="form-control" 
                                       value="{{ old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Origen Vuelo (Opcional)</label>
                                <input type="text" name="origen_vuelo_entrada" class="form-control" 
                                       value="{{ old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada) }}">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection