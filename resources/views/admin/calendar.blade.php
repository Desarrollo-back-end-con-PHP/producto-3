@extends('layouts.admin')

@section('title', 'Calendario de Reservas')

@section('content')
<div class="container py-4">

    {{-- CABECERA: Título, Buscador y Botones de Vista --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <div class="row align-items-center g-3">
                <div class="col-lg-4">
                    <h2 class="mb-0 fw-bold text-primary">{{ $tituloCalendario }}</h2>
                    <p class="text-muted mb-0 small">Vista: {{ ucfirst($vista) }}</p>
                </div>
                
                {{-- Buscador por Hotel --}}
                <div class="col-lg-4">
                    <form action="{{ route('admin.calendar') }}" method="GET" class="d-flex gap-2">
                        <input type="hidden" name="vista" value="{{ $vista }}">
                        <input type="text" name="busqueda" class="form-control form-control-sm" placeholder="Buscar hotel..." value="{{ $busqueda ?? '' }}">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                        @if($busqueda)
                            <a href="{{ route('admin.calendar', ['vista' => $vista]) }}" class="btn btn-outline-danger btn-sm"><i class="fa fa-times"></i></a>
                        @endif
                    </form>
                </div>

                <div class="col-lg-4 d-flex justify-content-lg-end gap-2">
                    {{-- Selector de Vistas --}}
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('admin.calendar', ['vista' => 'mes', 'ano' => $fechaBase->year, 'mes' => $fechaBase->month]) }}"
                           class="btn btn-sm {{ $vista == 'mes' ? 'btn-primary' : 'btn-outline-primary' }}">Mes</a>
                        <a href="{{ route('admin.calendar', ['vista' => 'semana', 'ano' => $fechaBase->year, 'mes' => $fechaBase->month, 'dia' => $fechaBase->day]) }}"
                           class="btn btn-sm {{ $vista == 'semana' ? 'btn-primary' : 'btn-outline-primary' }}">Semana</a>
                        <a href="{{ route('admin.calendar', ['vista' => 'dia', 'ano' => $fechaBase->year, 'mes' => $fechaBase->month, 'dia' => $fechaBase->day]) }}"
                           class="btn btn-sm {{ $vista == 'dia' ? 'btn-primary' : 'btn-outline-primary' }}">Día</a>
                    </div>

                    {{-- Navegación --}}
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('admin.calendar', ['vista' => $vista, 'ano' => $navAnterior->year, 'mes' => $navAnterior->month, 'dia' => $navAnterior->day]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                        <a href="{{ route('admin.calendar', ['vista' => $vista, 'ano' => $navSiguiente->year, 'mes' => $navSiguiente->month, 'dia' => $navSiguiente->day]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <style>
                .calendar-table { table-layout: fixed; width: 100%; border-collapse: collapse; }
                .calendar-table th { text-align: center; background: #f8f9fa; padding: 12px; border-bottom: 2px solid #eee; font-size: 0.9rem; color: #666; }
                .calendar-table td {
                    height: {{ $vista == 'dia' ? '450px' : '130px' }};
                    vertical-align: top; border: 1px solid #dee2e6; padding: 8px; position: relative; transition: background 0.2s;
                }
                .calendar-table td:hover { background-color: #fcfcfc; }
                .day-number { font-weight: 800; color: #333; font-size: 0.9rem; }
                
                /* Estilos de los Badges */
                .reserva-badge {
                    font-size: 0.72rem; display: block; margin-bottom: 4px;
                    text-decoration: none; color: white !important; padding: 4px 6px;
                    border-radius: 4px; border-left: 4px solid rgba(0,0,0,0.2);
                    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                }
                .reserva-badge:hover { opacity: 0.9; transform: translateY(-1px); }
                
                /* Colores de Estado */
                .status-activa { background-color: #198754; } 
                .status-pendiente { background-color: #ffc107; color: #000 !important; }
                .status-cancelada { background-color: #6c757d; } /* Gris para canceladas */
                
                .td-hoy { background-color: #e8f4ff !important; border: 2px solid #0d6efd !important; }
            </style>

            <table class="calendar-table mb-0">
                @if($vista != 'dia')
                <thead>
                    <tr><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr>
                </thead>
                @endif

                <tbody>
                    @php $fechaActual = $inicioGrid->copy(); @endphp
                    @while($fechaActual <= $finGrid)
                        <tr>
                            @for($i = 0; $i < ($vista == 'dia' ? 1 : 7); $i++)
                                @if($fechaActual <= $finGrid)
                                    @php
                                        $esMesActual = $fechaActual->month == $fechaBase->month;
                                        $fechaString = $fechaActual->format('Y-m-d');
                                        $esHoy = $fechaActual->isToday();
                                    @endphp

                                    <td class="{{ ($esMesActual || $vista != 'mes') ? 'bg-white' : 'bg-light' }} {{ $esHoy ? 'td-hoy' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <a href="{{ route('admin.calendar', ['vista' => 'dia', 'ano' => $fechaActual->year, 'mes' => $fechaActual->month, 'dia' => $fechaActual->day]) }}" class="day-number text-decoration-none">
                                                {{ $fechaActual->day }}
                                                @if($vista == 'dia') {{ $fechaActual->translatedFormat('l') }} @endif
                                            </a>
                                            <a href="{{ route('admin.reservas.create', ['fecha' => $fechaString]) }}" class="text-success small" title="Crear Reserva">
                                                <i class="fa fa-plus-circle"></i>
                                            </a>
                                        </div>

                                        <div class="reserva-container" style="max-height: 100px; overflow-y: auto; scrollbar-width: none;">
                                            @if(isset($reservasPorDia[$fechaString]))
                                                @foreach($reservasPorDia[$fechaString] as $reserva)
                                                    @php
                                                        $status = strtolower($reserva->status ?? 'pendiente');
                                                        $classStatus = ($status == 'cancelada') ? 'status-cancelada' : (($status == 'activa') ? 'status-activa' : 'status-pendiente');
                                                        
                                                        $esSalida = ($reserva->fecha_vuelo_salida && $reserva->fecha_vuelo_salida->format('Y-m-d') == $fechaString);
                                                        $hora = $esSalida ? $reserva->hora_vuelo_salida : $reserva->hora_entrada;
                                                        $nombreHotel = $reserva->destino->usuario ?? ($reserva->hotel->usuario ?? 'Hotel');
                                                    @endphp

                                                    <a href="{{ route('admin.reservas.edit', $reserva->id_reserva) }}" class="reserva-badge {{ $classStatus }}">
                                                        <strong>
                                                            {{ $esSalida ? '🛫 Salida' : '🛬 Llegada' }} 
                                                            {{ \Carbon\Carbon::parse($hora)->format('H:i') }}
                                                        </strong>
                                                        <div class="text-truncate">{{ $nombreHotel }}</div>
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                @endif
                                @php $fechaActual->addDay(); @endphp
                            @endfor
                        </tr>
                    @endwhile
                </tbody>
            </table>
        </div>
    </div>

    {{-- LEYENDA INFORMATIVA --}}
    <div class="mt-4 d-flex flex-wrap justify-content-center gap-4 bg-white p-3 rounded shadow-sm border">
        <div class="d-flex align-items-center gap-2">
            <div style="width:14px; height:14px; background-color:#198754; border-radius:3px;"></div>
            <span class="small fw-bold">Activa / Confirmada</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div style="width:14px; height:14px; background-color:#ffc107; border-radius:3px;"></div>
            <span class="small fw-bold">Pendiente</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div style="width:14px; height:14px; background-color:#6c757d; border-radius:3px;"></div>
            <span class="small fw-bold">Cancelada</span>
        </div>
        <div class="ms-lg-4 border-start ps-4">
            <span class="small text-muted"><strong>🛬 Llegada</strong> (Entrada) | <strong>🛫 Salida</strong> (Vuelo)</span>
        </div>
    </div>
</div>
@endsection