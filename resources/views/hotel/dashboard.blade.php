@extends('layouts.app')

@section('title', 'Panel Corporativo - ' . $hotel->usuario)

@section('content')
<div class="container py-5">

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-primary">
                <i class="fas fa-hotel me-2"></i> {{ $hotel->usuario }}
            </h1>
            <p class="text-muted">Bienvenido a tu panel de gestión corporativa.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('hotel.reservas.create') }}" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Nueva Reserva
            </a>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-ticket-alt me-2"></i> Reservas Totales</h5>
                    <p class="display-4 fw-bold">{{ $reservas->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-euro-sign me-2"></i> Comisión por Reserva</h5>
                    <p class="display-4 fw-bold">{{ $hotel->Comision }} €</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark shadow h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-chart-line me-2"></i> Total Generado</h5>
                    <p class="display-4 fw-bold">{{ number_format($totalComisiones, 2) }} €</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Historial de Reservas</h5>
        </div>
        <div class="card-body">
            @if($reservas->isEmpty())
            <p class="text-center text-muted my-4">Aún no has realizado ninguna reserva.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Localizador</th>
                            <th>Fecha Reserva</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Vehículo</th>
                            <th>Estado</th>
                            <th class="text-end">Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                        <tr>
                            <td class="fw-bold text-primary">{{ $reserva->localizador }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</td>
                            <td>{{ $reserva->email_cliente }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $reserva->tipo->descripcion ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $reserva->vehiculo->descripcion ?? 'Asignando...' }}</td>
                            <td>
                                @if($reserva->status == 'confirmada')
                                <span class="badge bg-success">Confirmada</span>
                                @elseif($reserva->status == 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                                @else
                                <span class="badge bg-secondary">{{ $reserva->status }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-success">
                                + {{ $hotel->Comision }} €
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection