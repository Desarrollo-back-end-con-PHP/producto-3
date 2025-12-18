@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Reserva: {{ $reserva->localizador }}</h4>
        </div>
        <div class="card-body p-4">

            {{-- IMPORTANTE: La ruta apunta a update y añadimos el ID --}}
            <form action="{{ route('admin.reservas.update', $reserva->id_reserva) }}" method="POST">
                @csrf
                @method('PUT') {{-- Directiva necesaria para actualizaciones --}}

                <div class="row g-3">
                    {{-- Hotel Comisión --}}
                    <div class="col-md-6">
                        <label class="form-label">Hotel (Comisionista)</label>
                        <select name="id_hotel_comision" class="form-select" required>
                            @foreach($hoteles as $hotel)
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $reserva->id_hotel == $hotel->id_hotel ? 'selected' : '' }}>
                                    {{ $hotel->usuario }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Email Cliente --}}
                    <div class="col-md-6">
                        <label class="form-label">Email Cliente</label>
                        <input type="email" name="email_cliente" class="form-control"
                               value="{{ $reserva->email_cliente }}" required>
                    </div>

                    {{-- Tipo Reserva --}}
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Reserva</label>
                        <select name="id_tipo_reserva" class="form-select" required>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo_reserva }}"
                                    {{ $reserva->id_tipo_reserva == $tipo->id_tipo_reserva ? 'selected' : '' }}>
                                    {{ $tipo->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Destino --}}
                    <div class="col-md-6">
                        <label class="form-label">Hotel Destino</label>
                        <select name="id_destino" class="form-select" required>
                            @foreach($hoteles as $h)
                                <option value="{{ $h->id_hotel }}"
                                    {{ $reserva->id_destino == $h->id_hotel ? 'selected' : '' }}>
                                    {{ $h->usuario }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Vehículo --}}
                    <div class="col-md-4">
                        <label class="form-label">Vehículo</label>
                        <select name="id_vehiculo" class="form-select" required>
                            @foreach($vehiculos as $v)
                                <option value="{{ $v->id_vehiculo }}"
                                    {{ $reserva->id_vehiculo == $v->id_vehiculo ? 'selected' : '' }}>
                                    {{ $v->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Viajeros --}}
                    <div class="col-md-2">
                        <label class="form-label">Viajeros</label>
                        <input type="number" name="num_viajeros" class="form-control"
                               value="{{ $reserva->num_viajeros }}" min="1" required>
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="confirmada" {{ $reserva->status == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="cancelada" {{ $reserva->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            <option value="pendiente" {{ $reserva->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        </select>
                    </div>

                    <div class="col-12"><hr></div>

                    {{-- Fechas --}}
                    <div class="col-md-6">
                        <label class="form-label">Fecha Entrada</label>
                        <input type="date" name="fecha_entrada" class="form-control"
                               value="{{ optional($reserva->fecha_entrada)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hora Entrada</label>
                        <input type="time" name="hora_entrada" class="form-control"
                               value="{{ $reserva->hora_entrada }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.calendar') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
