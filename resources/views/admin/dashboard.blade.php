@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">

    <h1 class="display-6 fw-bold mb-4">Información General</h1>

    <div class="row g-4 mb-5">

        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body">
                    <h5 class="card-title fs-4">Reservas Totales</h5>
                    <p class="card-text display-4 fw-bold">{{ $totalReservas }}</p>
                    <a href="{{ route('admin.reservas.index') }}" class="stretched-link text-white text-decoration-none small">Ver detalles &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-secondary shadow h-100">
                <div class="card-body">
                    <h5 class="card-title fs-4">Hoteles Activos</h5>
                    <p class="card-text display-4 fw-bold">{{ $totalHoteles }}</p>
                    <a href="{{ route('admin.hoteles.index') }}" class="stretched-link text-white text-decoration-none small">Gestionar Hoteles &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-4">
            <div class="card text-white bg-info shadow h-100">
                <div class="card-body">
                    <h5 class="card-title fs-4">Usuarios Registrados</h5>
                    <p class="card-text display-4 fw-bold">{{ $totalUsuarios }}</p>
                    <a href="#" class="stretched-link text-white text-decoration-none small">Ver Usuarios &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 rounded-lg">
        <div class="card-header bg-white py-3">
            <h3 class="h5 m-0 fw-bold text-secondary">Próximas Reservas</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Localizador</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                        <tr>
                            <td class="fw-bold">{{ $reserva->localizador }}</td>
                            <td>{{ $reserva->email_cliente }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}
                                <small class="text-muted">{{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}</small>
                            </td>

                            <td>
                                @if($reserva->id_tipo_reserva == 2) {{ $reserva->hotel->usuario ?? 'Hotel' }}
                                @else
                                Aeropuerto
                                @endif
                            </td>
                            <td>
                                @if($reserva->id_tipo_reserva == 1 || $reserva->id_tipo_reserva == 3) {{ $reserva->destino->usuario ?? 'Hotel' }}
                                @else
                                Aeropuerto
                                @endif
                            </td>

                            <td><span class="badge bg-success">Confirmada</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay reservas próximas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection