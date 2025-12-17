@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container py-5">

    {{-- ENCABEZADO CON BOTONES DE ACCIÓN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold">Panel de Administración</h1>

        <div>
            {{-- Botón para ir al CALENDARIO --}}
            <a href="{{ route('admin.calendar') }}" class="btn btn-primary me-2 shadow-sm">
                <i class="fa fa-calendar-alt"></i> Ver Calendario
            </a>

            {{-- Botón para ir al REPORTE DE COMISIONES --}}
            <a href="{{ route('admin.comisiones') }}" class="btn btn-success shadow-sm">
                <i class="fa fa-chart-line"></i> Comisiones
            </a>
        </div>
    </div>

    {{-- TARJETAS DE ESTADÍSTICAS GLOBALES --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Reservas</h5>
                    <p class="display-4 fw-bold">{{ $totalReservas ?? '0' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-secondary shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Hoteles Activos</h5>
                    <p class="display-4 fw-bold">{{ $totalHoteles ?? '0' }}</p>
                    <a href="{{ route('admin.hoteles.index') }}" class="text-white text-decoration-none small stretched-link">Gestionar Hoteles &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Viajeros Registrados</h5>
                    <p class="display-4 fw-bold">{{ $totalUsuarios ?? '0' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE ÚLTIMAS RESERVAS --}}
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 fw-bold text-secondary">Últimas Reservas (Global)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Localizador</th>
                            <th>Fecha</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($reservas) && count($reservas) > 0)
                            @foreach($reservas as $reserva)
                            <tr>
                                <td class="fw-bold">{{ $reserva->localizador }}</td>
                                <td>{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($reserva->id_tipo_reserva == 2 && $reserva->hotel)
                                        {{ $reserva->hotel->usuario }}
                                    @else
                                        Aeropuerto
                                    @endif
                                </td>
                                <td>
                                    @if(($reserva->id_tipo_reserva == 1 || $reserva->id_tipo_reserva == 3) && $reserva->destino)
                                        {{ $reserva->destino->usuario }}
                                    @else
                                        Aeropuerto
                                    @endif
                                </td>
                                <td>{{ $reserva->email_cliente }}</td>
                                <td><span class="badge bg-success">{{ $reserva->status }}</span></td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay reservas recientes.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
