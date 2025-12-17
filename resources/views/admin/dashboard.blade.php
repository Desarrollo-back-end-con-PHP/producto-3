@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')
<div class="container py-5">

    {{-- 1. ENCABEZADO (Mismo estilo que Hotel: Título negrita + Botones con sombra) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark fw-bold mb-0">Dashboard</h1>
            <p class="text-muted small">Resumen general de la plataforma.</p>
        </div>
        <div>
            <a href="{{ route('admin.calendar') }}" class="btn btn-primary shadow-sm me-2">
                <i class="fas fa-calendar-alt me-2"></i>Calendario
            </a>
            <a href="{{ route('admin.comisiones') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-chart-line me-2"></i>Comisiones
            </a>
        </div>
    </div>

    {{-- 2. TARJETAS / KPIS (Estilo idéntico: Borde izquierdo grueso, icono grande a la derecha) --}}
    <div class="row g-4 mb-5">
        
        {{-- Tarjeta 1: Total Reservas --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase small fw-bold text-muted mb-1">Total Reservas</div>
                            <div class="h2 mb-0 fw-bold text-dark">{{ $totalReservas ?? '0' }}</div>
                        </div>
                        <div class="text-primary opacity-50">
                            <i class="fas fa-calendar-check fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Hoteles Activos --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase small fw-bold text-muted mb-1">Hoteles Activos</div>
                            <div class="h2 mb-0 fw-bold text-dark">{{ $totalHoteles ?? '0' }}</div>
                        </div>
                        <div class="text-success opacity-50">
                            <i class="fas fa-hotel fa-3x"></i>
                        </div>
                    </div>
                    {{-- Link extendido para hacer clic en toda la tarjeta --}}
                    <a href="{{ route('admin.hoteles.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Viajeros Registrados --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase small fw-bold text-muted mb-1">Viajeros Registrados</div>
                            <div class="h2 mb-0 fw-bold text-dark">{{ $totalUsuarios ?? '0' }}</div>
                        </div>
                        <div class="text-info opacity-50">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.viajeros.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. TABLA DE ÚLTIMAS RESERVAS (Estilo idéntico: Header blanco, hover, badges con opacidad) --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Últimas Reservas (Global)</h5>
            <a href="{{ route('admin.reservas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4">Localizador</th>
                            <th>Fecha</th>
                            <th>Hora</th>
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
                                <td class="ps-4 fw-bold text-primary font-monospace">{{ $reserva->localizador }}</td>

                                {{-- FECHA --}}
                                <td>{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</td>

                                {{-- HORA --}}
                                <td><small class="text-muted">{{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}</small></td>

                                {{-- ORIGEN --}}
                                <td>
                                    @if($reserva->id_tipo_reserva == 2 && $reserva->hotel)
                                        <div class="small fw-bold text-dark"><i class="fas fa-hotel text-secondary me-1"></i> {{ $reserva->hotel->usuario }}</div>
                                    @else
                                        <div class="small"><i class="fas fa-plane-arrival text-secondary me-1"></i> Aeropuerto</div>
                                    @endif
                                </td>

                                {{-- DESTINO --}}
                                <td>
                                    @if(($reserva->id_tipo_reserva == 1 || $reserva->id_tipo_reserva == 3) && $reserva->destino)
                                        <div class="small fw-bold text-dark"><i class="fas fa-hotel text-secondary me-1"></i> {{ $reserva->destino->usuario }}</div>
                                    @else
                                        <div class="small"><i class="fas fa-plane-departure text-secondary me-1"></i> Aeropuerto</div>
                                    @endif
                                </td>

                                {{-- CLIENTE --}}
                                <td>
                                    <div class="small text-truncate" style="max-width: 150px;">{{ $reserva->email_cliente }}</div>
                                </td>

                                {{-- ESTADO (Badges estilo moderno con opacidad) --}}
                                <td>
                                    @if($reserva->status == 'confirmada')
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Confirmada</span>
                                    @elseif($reserva->status == 'pendiente')
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 text-dark">Pendiente</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">{{ $reserva->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0">No hay reservas recientes.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection