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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 4. TABLA DE RESERVAS --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="mb-0 fw-bold text-secondary">Últimas Reservas</h5>

            {{-- BOTONES DE FILTRO DE ESTADO --}}
            <div class="btn-group shadow-sm" role="group">
                <a href="{{ route('hotel.panel') }}" class="btn btn-sm btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Todas</a>
                <a href="{{ route('hotel.panel', ['status' => 'pendiente']) }}" class="btn btn-sm btn-outline-warning {{ request('status') == 'pendiente' ? 'active' : '' }}">Pendientes</a>
                <a href="{{ route('hotel.panel', ['status' => 'confirmada']) }}" class="btn btn-sm btn-outline-success {{ request('status') == 'confirmada' ? 'active' : '' }}">Confirmadas</a>
                <a href="{{ route('hotel.panel', ['status' => 'cancelada']) }}" class="btn btn-sm btn-outline-danger {{ request('status') == 'cancelada' ? 'active' : '' }}">Canceladas</a>
            </div>
        </div>

        <div class="card-body p-0">
            @if($reservas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">No hay reservas registradas.</p>
                </div>
            @else
                {{-- CONTENEDOR CON SCROLL (Altura máxima 500px) --}}
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        {{-- CABECERA STICKY (Se queda arriba al hacer scroll) --}}
                        <thead class="bg-dark text-white text-uppercase small" style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th class="ps-4 py-3">Ref.</th>
                                <th>Canal / Origen</th>
                                <th>Fecha Servicio</th>
                                <th>Detalles Servicio</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th class="text-center pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                            @php
                                $estado = strtolower(trim($reserva->status));
                                $esWeb = is_null($reserva->id_hotel);
                                $esAdmin = ($reserva->id_hotel && !str_starts_with($reserva->localizador, 'HTL-'));
                            @endphp
                            {{-- Resaltado suave para filas pendientes --}}
                            <tr @if($estado == 'pendiente') style="background-color: rgba(255, 193, 7, 0.05);" @endif>
                                <td class="ps-4 fw-bold text-primary font-monospace">{{ $reserva->localizador }}</td>
                                
                                <td>
                                    @if($esWeb)
                                        <span class="badge shadow-sm" style="background-color: #0d6efd; color: white; padding: 0.5rem 1rem; border-radius: 50px;">
                                            <i class="fas fa-globe me-1"></i> CLIENTE WEB
                                        </span>
                                    @elseif($esAdmin)
                                        <span class="badge shadow-sm" style="background-color: #ffc107; color: #000; padding: 0.5rem 1rem; border-radius: 50px;">
                                            <i class="fas fa-user-shield me-1"></i> ADMINISTRADOR
                                        </span>
                                    @else
                                        <span class="badge shadow-sm" style="background-color: #198754; color: white; padding: 0.5rem 1rem; border-radius: 50px;">
                                            <i class="fas fa-hotel me-1"></i> PROPIA (HOTEL)
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</div>
                                    <div class="badge bg-secondary small"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}</div>
                                </td>

                                <td>
                                    @if($reserva->id_tipo_reserva == 1 || $reserva->id_tipo_reserva == 3)
                                        <div class="text-primary fw-bold small"><i class="fas fa-plane-arrival me-1"></i>LLEGADA</div>
                                    @else
                                        <div class="text-warning fw-bold small"><i class="fas fa-plane-departure me-1"></i>SALIDA</div>
                                    @endif
                                    <div class="small text-muted text-uppercase" style="font-size: 0.75rem;">{{ $reserva->vehiculo->modelo ?? 'Vehículo' }}</div>
                                </td>

                                <td>
                                    <div class="fw-bold text-dark mb-0">{{ $reserva->email_cliente }}</div>
                                    <small class="text-muted"><i class="fas fa-users me-1"></i>{{ $reserva->num_viajeros }} pax</small>
                                </td>

                                <td>
                                    @if($estado == 'confirmada' || $estado == 'activa')
                                        <span class="badge bg-success w-100 py-2">Confirmada</span>
                                    @elseif($estado == 'cancelada')
                                        <span class="badge bg-danger w-100 py-2">Cancelada</span>
                                    @else
                                        <span class="badge bg-warning text-dark w-100 py-2">Pendiente</span>
                                    @endif
                                </td>

                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        @if($estado == 'pendiente')
                                            <form action="{{ route('hotel.reservas.aceptar', $reserva->id_reserva) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm px-3" title="Confirmar" onclick="return confirm('¿Confirmar esta reserva?')">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($estado != 'cancelada')
                                            <a href="{{ route('hotel.reservas.edit', $reserva->id_reserva) }}" class="btn btn-primary btn-sm px-3" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('hotel.reservas.cancel', $reserva->id_reserva) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Anular esta reserva?');">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm px-3" title="Anular">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- PAGINACIÓN (Fuera del scroll para que no se pierda) --}}
                <div class="p-3 border-top bg-light text-center">
                    {{ $reservas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection