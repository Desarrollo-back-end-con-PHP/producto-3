@extends('layouts.admin')

@section('title', 'Reporte Mensual de Comisiones')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>💰 Reporte Mensual de Comisiones</h2>
        <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary">Volver al Listado</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            @if($informe->isEmpty())
                <div class="alert alert-info text-center">
                    No hay datos suficientes para generar el reporte de comisiones.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Periodo</th>
                                <th>Hotel (Usuario)</th>
                                <th class="text-center">Total Reservas</th>
                                <th class="text-center">Comisión Base</th>
                                <th class="text-end">TOTAL A PAGAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($informe as $fila)
                                @php
                                    // Truco para escribir el nombre del mes
                                    $nombreMes = \Carbon\Carbon::createFromDate($fila->anio, $fila->mes, 1)->translatedFormat('F Y');
                                @endphp
                                <tr>
                                    <td class="fw-bold text-secondary">{{ ucfirst($nombreMes) }}</td>
                                    <td>{{ $fila->nombre_hotel }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $fila->total_reservas }}</span>
                                    </td>
                                    <td class="text-center">{{ number_format($fila->comision_base, 2) }} €</td>
                                    <td class="text-end fw-bold text-success fs-5">
                                        {{ number_format($fila->total_pagar, 2) }} €
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