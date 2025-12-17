@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')

    {{-- ENCABEZADO CON BOTONES DE ACCIÓN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Dashboard</h1>
            <p class="text-muted small mb-0">Resumen general de la plataforma.</p>
        </div>

        <div>
            <a href="{{ route('admin.calendar') }}" class="btn btn-primary me-2 shadow-sm btn-sm">
                <i class="fa fa-calendar-alt fa-sm text-white-50 me-1"></i> Calendario
            </a>
            <a href="{{ route('admin.comisiones') }}" class="btn btn-success shadow-sm btn-sm">
                <i class="fa fa-chart-line fa-sm text-white-50 me-1"></i> Comisiones
            </a>
        </div>
    </div>

    {{-- TARJETAS DE ESTADÍSTICAS GLOBALES --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-4 border-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Reservas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReservas ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-4 border-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hoteles Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHoteles ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hotel fa-2x text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.hoteles.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-4 border-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Viajeros Registrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsuarios ?? '0' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.viajeros.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE ÚLTIMAS RESERVAS --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Últimas Reservas (Global)</h6>
            <a href="{{ route('admin.reservas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
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
                                <td class="ps-4 fw-bold text-primary">{{ $reserva->localizador }}</td>

                                {{-- FECHA --}}
                                <td>{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</td>

                                {{-- HORA --}}
                                <td>{{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}</td>

                                {{-- ORIGEN --}}
                                <td>
                                    @if($reserva->id_tipo_reserva == 2 && $reserva->hotel)
                                        <i class="fas fa-hotel text-secondary me-1"></i> {{ $reserva->hotel->usuario }}
                                    @else
                                        <i class="fas fa-plane-arrival text-secondary me-1"></i> Aeropuerto
                                    @endif
                                </td>

                                {{-- DESTINO --}}
                                <td>
                                    @if(($reserva->id_tipo_reserva == 1 || $reserva->id_tipo_reserva == 3) && $reserva->destino)
                                        <i class="fas fa-hotel text-secondary me-1"></i> {{ $reserva->destino->usuario }}
                                    @else
                                        <i class="fas fa-plane-departure text-secondary me-1"></i> Aeropuerto
                                    @endif
                                </td>

                                {{-- CLIENTE --}}
                                <td>
                                    <div class="small">{{ $reserva->email_cliente }}</div>
                                </td>

                                {{-- ESTADO --}}
                                <td>
                                    @if($reserva->status == 'confirmada')
                                        <span class="badge bg-success bg-opacity-10 text-success">Confirmada</span>
                                    @elseif($reserva->status == 'pendiente')
                                        <span class="badge bg-warning bg-opacity-10 text-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $reserva->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                                    <p class="mb-0">No hay reservas recientes.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection