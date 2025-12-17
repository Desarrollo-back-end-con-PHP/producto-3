@extends('layouts.app')

@section('title', 'Calendario de Reservas')

@section('content')
<div class="container py-4">

    {{-- CABECERA: Título y Botones de Vista --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="mb-0 fw-bold">{{ $tituloCalendario }}</h2>
            <p class="text-muted mb-0">Vista: {{ ucfirst($vista) }}</p>
        </div>

        <div class="d-flex gap-2">
            {{-- Selector de Vistas --}}
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.calendar', ['vista' => 'mes', 'ano' => $fechaBase->year, 'mes' => $fechaBase->month]) }}"
                   class="btn {{ $vista == 'mes' ? 'btn-primary' : 'btn-outline-primary' }}">Mes</a>

                <a href="{{ route('admin.calendar', ['vista' => 'semana', 'ano' => $fechaBase->year, 'mes' => $fechaBase->month, 'dia' => $fechaBase->day]) }}"
                   class="btn {{ $vista == 'semana' ? 'btn-primary' : 'btn-outline-primary' }}">Semana</a>

                <a href="{{ route('admin.calendar', ['vista' => 'dia', 'ano' => $fechaBase->year, 'mes' => $fechaBase->month, 'dia' => $fechaBase->day]) }}"
                   class="btn {{ $vista == 'dia' ? 'btn-primary' : 'btn-outline-primary' }}">Día</a>
            </div>

            {{-- Navegación Anterior / Siguiente --}}
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.calendar', ['vista' => $vista, 'ano' => $navAnterior->year, 'mes' => $navAnterior->month, 'dia' => $navAnterior->day]) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-chevron-left"></i>
                </a>

                <a href="{{ route('admin.calendar') }}" class="btn btn-outline-secondary">Hoy</a>

                <a href="{{ route('admin.calendar', ['vista' => $vista, 'ano' => $navSiguiente->year, 'mes' => $navSiguiente->month, 'dia' => $navSiguiente->day]) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <style>
                .calendar-table { table-layout: fixed; width: 100%; }
                .calendar-table th { text-align: center; background: #f8f9fa; padding: 12px; border-bottom: 2px solid #eee; }
                /* Altura dinámica: Si es vista día, hacemos la celda enorme para que se vea detalle */
                .calendar-table td {
                    height: {{ $vista == 'dia' ? '400px' : '120px' }};
                    vertical-align: top; border: 1px solid #dee2e6; padding: 8px; position: relative; transition: background 0.2s;
                }
                .calendar-table td:hover { background-color: #fcfcfc; }
                .day-number { font-weight: bold; margin-bottom: 8px; display: block; color: #444; }
                .reserva-badge {
                    font-size: 0.8rem; display: block; margin-bottom: 3px;
                    text-decoration: none; color: white !important; padding: 4px 8px;
                    border-radius: 4px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
                    box-shadow: 1px 1px 2px rgba(0,0,0,0.1);
                }
                .reserva-badge:hover { opacity: 0.9; transform: translateY(-1px); }
                .bg-salida { background-color: #dc3545; border-left: 3px solid #a71d2a; }
                .bg-llegada { background-color: #198754; border-left: 3px solid #0f5132; }

                /* Estilo especial para cuando es HOY */
                .td-hoy { background-color: #e8f4ff !important; border: 2px solid #0d6efd !important; }
            </style>

            <table class="calendar-table mb-0">
                @if($vista != 'dia')
                <thead>
                    <tr>
                        <th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th><th>Sábado</th><th>Domingo</th>
                    </tr>
                </thead>
                @endif

                <tbody>
                    @php
                        $fechaActual = $inicioGrid->copy();
                    @endphp

                    @while($fechaActual <= $finGrid)
                        <tr>
                            {{-- Si es vista DIA, solo hacemos 1 iteración, si no, 7 --}}
                            @for($i = 0; $i < ($vista == 'dia' ? 1 : 7); $i++)
                                @if($fechaActual <= $finGrid)
                                    @php
                                        $esMesActual = $fechaActual->month == $fechaBase->month;
                                        $fechaString = $fechaActual->format('Y-m-d');
                                        $esHoy = $fechaActual->isToday();
                                    @endphp

                                    <td class="{{ ($esMesActual || $vista != 'mes') ? 'bg-white' : 'bg-light' }} {{ $esHoy ? 'td-hoy' : '' }}">

                                        <div class="d-flex justify-content-between">
                                            {{-- El número lleva a la vista de DÍA de ese día específico --}}
                                            <a href="{{ route('admin.calendar', ['vista' => 'dia', 'ano' => $fechaActual->year, 'mes' => $fechaActual->month, 'dia' => $fechaActual->day]) }}" class="day-number text-decoration-none">
                                                {{ $fechaActual->day }}
                                                @if($vista == 'dia') {{ $fechaActual->translatedFormat('l') }} @endif
                                            </a>

                                            {{-- Botón + para crear reserva --}}
                                            <a href="{{ route('admin.reservas.create', ['fecha' => $fechaString]) }}" class="text-success" title="Crear Reserva">
                                                <i class="fa fa-plus-circle"></i>
                                            </a>
                                        </div>

                                        {{-- LISTADO RESERVAS --}}
                                        <div class="mt-2">
                                            @if(isset($reservasPorDia[$fechaString]))
                                                @foreach($reservasPorDia[$fechaString] as $reserva)
                                                    @php
                                                        $esSalida = ($reserva->fecha_vuelo_salida && $reserva->fecha_vuelo_salida->format('Y-m-d') == $fechaString);
                                                        $clase = $esSalida ? 'bg-salida' : 'bg-llegada';
                                                        $icono = $esSalida ? '🛫' : '🛬';
                                                        $texto = $esSalida ? 'Salida' : ($reserva->destino->usuario ?? 'Llegada');
                                                    @endphp

                                                    <a href="{{ route('admin.reservas.edit', $reserva->id_reserva) }}" class="reserva-badge {{ $clase }}">
                                                        {{ $icono }}
                                                        <strong>
                                                            {{ \Carbon\Carbon::parse($esSalida ? $reserva->hora_vuelo_salida : $reserva->hora_entrada)->format('H:i') }}
                                                        </strong>
                                                        - {{ $texto }}
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
</div>
@endsection
