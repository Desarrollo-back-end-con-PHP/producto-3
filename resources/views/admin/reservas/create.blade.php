@extends('layouts.app')

@section('title', 'Nueva Reserva Manual')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Crear Reserva (Admin)</h4>
                </div>
                <div class="card-body p-4">

                    {{-- 1. ESTO ES VITAL PARA VER POR QUÉ FALLA --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.reservas.store') }}" method="POST">
                        @csrf

                        {{-- 2. EL CAMPO CLAVE: id_hotel_comision --}}
                        <div class="mb-4 bg-light p-3 rounded border">
                            <label class="form-label fw-bold text-primary">¿A qué Hotel asignamos la comisión?</label>
                            <select name="id_hotel_comision" class="form-select" required>
                                <option value="">-- Selecciona el Hotel --</option>
                                @foreach($hoteles as $h)
                                    {{-- Si falla, mantenemos el valor seleccionado con old() --}}
                                    <option value="{{ $h->id_hotel }}" {{ old('id_hotel_comision') == $h->id_hotel ? 'selected' : '' }}>
                                        {{ $h->usuario }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Este es el hotel que cobrará por esta reserva.</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha Entrada</label>
                                <input type="date" name="fecha_entrada" class="form-control"
                                       value="{{ $fechaPreseleccionada ?? date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Entrada</label>
                                <input type="time" name="hora_entrada" class="form-control" value="12:00" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Email Cliente</label>
                                <input type="email" name="email_cliente" class="form-control" required value="{{ old('email_cliente') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo Reserva</label>
                                <select name="id_tipo_reserva" class="form-select" required>
                                    @foreach($tipos as $t)
                                        <option value="{{ $t->id_tipo_reserva }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vehículo</label>
                                <select name="id_vehiculo" class="form-select" required>
                                    @foreach($vehiculos as $v)
                                        <option value="{{ $v->id_vehiculo }}">{{ $v->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Destino (Lugar)</label>
                                <select name="id_destino" class="form-select" required>
                                    @foreach($hoteles as $h)
                                        <option value="{{ $h->id_hotel }}">{{ $h->usuario }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nº Viajeros</label>
                                <input type="number" name="num_viajeros" class="form-control" value="1" required min="1">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.calendar') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Reserva</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
