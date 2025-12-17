@extends('layouts.hotel') 

@section('title', 'Dashboard - ' . $hotel->usuario)

@section('content')
<div class="container py-5">

    {{-- 1. ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark fw-bold mb-0">Panel de Control</h1>
            <p class="text-muted small">Resumen de actividad y comisiones.</p>
        </div>
        <div>
            <a href="{{ route('hotel.reservas.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i>Crear Reserva
            </a>
        </div>
    </div>

        {{-- SECCIÓN DE SEGURIDAD (CAMBIO DE CONTRASEÑA) --}}
    <div class="row mb-5">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 fs-6 fw-bold"><i class="fas fa-lock me-2 text-warning"></i>Cambiar Contraseña</h5>
                </div>
                <div class="card-body bg-light">
                    
                    @if (session('status_password'))
                        <div class="alert alert-success small py-2 mb-3">
                            <i class="fas fa-check me-1"></i> {{ session('status_password') }}
                        </div>
                    @endif

                    <form action="{{ route('hotel.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Contraseña Actual</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Nueva</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">Repetir</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-secondary btn-sm fw-bold">Actualizar Clave</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7 d-flex align-items-center justify-content-center text-muted opacity-50 d-none d-lg-flex">
            <div class="text-center">
                <i class="fas fa-shield-alt fa-5x mb-3"></i>
                <p>Mantén tu cuenta segura actualizando tu contraseña periódicamente.</p>
            </div>
        </div>
    </div>

    {{-- 2. TARJETAS DE ESTADÍSTICAS --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase small fw-bold text-muted mb-1">Reservas Totales</div>
                            <div class="h2 mb-0 fw-bold text-dark">{{ $reservas->total() }}</div>
                        </div>
                        <div class="text-primary opacity-50"><i class="fas fa-ticket-alt fa-3x"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase small fw-bold text-muted mb-1">Comisión (Este Mes)</div>
                            <div class="h2 mb-0 fw-bold text-success">{{ number_format($comisionMensual, 2, ',', '.') }} €</div>
                        </div>
                        <div class="text-success opacity-50"><i class="fas fa-calendar-day fa-3x"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-dark border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase small fw-bold text-muted mb-1">Total Generado</div>
                            <div class="h2 mb-0 fw-bold text-dark">{{ number_format($totalComisiones, 2, ',', '.') }} €</div>
                        </div>
                        <div class="text-dark opacity-50"><i class="fas fa-wallet fa-3x"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MENSAJES DE ERROR/EXITO GLOBAL --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 4. TABLA DE RESERVAS --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-secondary">Últimas Reservas</h5>
        </div>
        <div class="card-body p-0">
            @if($reservas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">No hay reservas registradas.</p>
                    <a href="{{ route('hotel.reservas.create') }}" class="btn btn-outline-primary btn-sm">Crear la primera</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-4">Ref.</th>
                                <th>Fecha Servicio</th>
                                <th>Detalles</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th class="text-center">Comisión</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                            <tr>
                                <td class="ps-4 fw-bold text-primary font-monospace">{{ $reserva->localizador }}</td>
                                <td>
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($reserva->destino)
                                        <div class="small fw-bold text-dark"><i class="fas fa-map-pin me-1 text-danger"></i> {{ $reserva->destino->usuario }}</div>
                                    @endif
                                    <span class="badge bg-light text-dark border mt-1">{{ $reserva->vehiculo->modelo ?? 'Auto' }}</span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 150px;">{{ $reserva->email_cliente }}</div>
                                    <small class="text-muted">{{ $reserva->num_viajeros }} pax</small>
                                </td>
                                <td>
                                    @if($reserva->status == 'confirmada')
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Confirmada</span>
                                    @elseif($reserva->status == 'cancelada')
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">Cancelada</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2 py-1">{{ ucfirst($reserva->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center text-success fw-bold">
                                    @if($reserva->status != 'cancelada')
                                        +{{ number_format($hotel->Comision, 2) }}€
                                    @else
                                        <span class="text-muted text-decoration-line-through small">{{ number_format($hotel->Comision, 2) }}€</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($reserva->status != 'cancelada')
                                        <div class="btn-group">
                                            <a href="{{ route('hotel.reservas.edit', $reserva->id_reserva) }}" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('hotel.reservas.cancel', $reserva->id_reserva) }}" method="POST" onsubmit="return confirm('¿Cancelar reserva?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger ms-1" title="Cancelar"><i class="fas fa-times"></i></button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $reservas->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection