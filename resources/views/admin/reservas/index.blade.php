@extends('layouts.app')

@section('title', 'Administración de Reservas')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800"><i class="fas fa-list-alt me-2"></i> Control de Reservas</h1>

        <div class="card bg-success text-white shadow">
            <div class="card-body py-2 px-4">
                <div class="text-uppercase small fw-bold">Comisión Total a Pagar</div>
                <div class="h2 mb-0 fw-bold">{{ number_format($totalComisionPagar, 2) }} €</div>
            </div>
        </div>
    </div>

    <div class="card mb-4 border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <form action="{{ route('admin.reservas.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Filtrar por Hotel:</label>
                    <select name="id_hotel" class="form-select">
                        <option value="">-- Ver Todas las Reservas --</option>
                        @foreach($hoteles as $h)
                        <option value="{{ $h->id_hotel }}" {{ request('id_hotel') == $h->id_hotel ? 'selected' : '' }}>
                            {{ $h->usuario }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i> Filtrar Listado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado Detallado</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Localizador</th>
                            <th>Fecha</th>
                            <th>Origen / Creador</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th class="text-end">Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                        <tr>
                            <td class="font-monospace fw-bold">{{ $reserva->localizador }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}</td>

                            <td>
                                @if($reserva->hotel)
                                <span class="badge bg-primary">HOTEL</span>
                                {{ $reserva->hotel->usuario }}
                                @else
                                <span class="badge bg-secondary">DIRECTA / WEB</span>
                                @endif
                            </td>

                            <td>{{ $reserva->email_cliente }}</td>

                            <td>
                                <span class="badge {{ $reserva->status == 'cancelada' ? 'bg-danger' : 'bg-success' }}">
                                    {{ ucfirst($reserva->status) }}
                                </span>
                            </td>

                            <td class="text-end fw-bold">
                                @if($reserva->hotel && $reserva->status != 'cancelada')
                                <span class="text-success">+ {{ $reserva->hotel->Comision }} €</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No se encontraron reservas con los filtros actuales.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection