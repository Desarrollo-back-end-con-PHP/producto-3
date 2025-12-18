@extends('layouts.app')

@section('title', 'Mis Reservas')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Mis Reservas</h2>
        <a href="{{ route('reservas.create') }}" class="btn btn-primary">Nueva Reserva</a>
    </div>

    @if(session('mensaje_exito'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje_exito') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @if($reservas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted opacity-50 mb-3"></i>
                    <p class="text-muted">Aún no tienes reservas realizadas.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Localizador</th>
                                <th>Fecha</th>
                                <th>Trayecto / Destino</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                            @php $estado = strtolower(trim($reserva->status)); @endphp
                            <tr>
                                <td class="ps-4 fw-bold text-primary">{{ $reserva->localizador }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="small">{{ $reserva->destino->usuario ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($estado == 'confirmada' || $estado == 'activa')
                                        <span class="badge bg-success">Confirmada</span>
                                    @elseif($estado == 'cancelada')
                                        <span class="badge bg-danger">Cancelada</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($estado != 'cancelada')
                                        <form action="{{ route('usuario.reservas.cancelar', $reserva->id_reserva) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres anular esta reserva?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Anular</button>
                                        </form>
                                    @endif
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