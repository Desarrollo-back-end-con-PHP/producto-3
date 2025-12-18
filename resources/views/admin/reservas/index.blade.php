@extends('layouts.admin')

@section('title', 'Administración de Reservas')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark fw-bold mb-0"><i class="fas fa-list-alt me-2 text-primary"></i>Control de Reservas</h1>
            <p class="text-muted small">Gestión global de traslados y comisiones.</p>
        </div>

        <div class="card bg-success text-white shadow border-0">
            <div class="card-body py-2 px-4 text-center">
                <div class="text-uppercase small fw-bold opacity-75">Comisión Total a Pagar</div>
                <div class="h2 mb-0 fw-bold">{{ number_format($totalComisionPagar, 2) }} €</div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-4 shadow-sm border-0 border-start border-primary border-4">
        <div class="card-body">
            <form action="{{ route('admin.reservas.index') }}" method="GET" class="row align-items-end g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold small text-muted text-uppercase">Filtrar por Hotel:</label>
                    <select name="id_hotel" class="form-select shadow-sm">
                        <option value="">-- Ver Todas las Reservas --</option>
                        @foreach($hoteles as $h)
                        <option value="{{ $h->id_hotel }}" {{ request('id_hotel') == $h->id_hotel ? 'selected' : '' }}>
                            {{ $h->usuario }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm fw-bold">
                        <i class="fas fa-filter me-2"></i> Filtrar Listado
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla Estilo Moderno --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Listado Detallado</h5>
            <span class="badge bg-light text-dark border small px-3 py-2">Total: {{ $reservas->count() }} registros</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3">Ref.</th>
                            <th>Canal / Origen</th>
                            <th>Fecha Servicio</th>
                            <th>Detalles / Cliente</th>
                            <th>Estado</th>
                            <th class="text-end">Comisión</th>
                            <th class="text-center pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $idsAdmin = \App\Models\TransferReserva::getReservasAdminIds(); @endphp
                        
                        @forelse($reservas as $reserva)
                        @php $estado = strtolower(trim($reserva->status)); @endphp
                        <tr>
                            {{-- LOCALIZADOR --}}
                            <td class="ps-4 fw-bold text-primary font-monospace">{{ $reserva->localizador }}</td>
                            
                            {{-- CANAL / ORIGEN (Colores diferenciados) --}}
                            <td>
                                @if(in_array($reserva->id_reserva, $idsAdmin))
                                    <span class="badge shadow-sm" style="background-color: #ffc107; color: #000; padding: 0.5rem 1rem; border-radius: 50px;">
                                        <i class="fas fa-user-shield me-1"></i> ADMIN
                                    </span>
                                @elseif($reserva->id_hotel)
                                    <span class="badge shadow-sm" style="background-color: #198754; color: white; padding: 0.5rem 1rem; border-radius: 50px;">
                                        <i class="fas fa-hotel me-1"></i> {{ $reserva->hotel->usuario }}
                                    </span>
                                @else
                                    <span class="badge shadow-sm" style="background-color: #0d6efd; color: white; padding: 0.5rem 1rem; border-radius: 50px;">
                                        <i class="fas fa-globe me-1"></i> CLIENTE WEB
                                    </span>
                                @endif
                            </td>

                            {{-- FECHA --}}
                            <td>
                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</div>
                                <div class="badge bg-secondary small shadow-sm"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('H:i') }}</div>
                            </td>

                            {{-- DETALLES / CLIENTE --}}
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $reserva->email_cliente }}</div>
                                @if($reserva->id_tipo_reserva == 1 || $reserva->id_tipo_reserva == 3)
                                    <small class="text-primary fw-bold"><i class="fas fa-plane-arrival me-1"></i>Llegada</small>
                                @else
                                    <small class="text-warning fw-bold"><i class="fas fa-plane-departure me-1"></i>Salida</small>
                                @endif
                            </td>

                            {{-- ESTADO --}}
                            <td>
                                @if($estado == 'confirmada' || $estado == 'activa')
                                    <span class="badge bg-success w-100 py-2 shadow-sm">Confirmada</span>
                                @elseif($estado == 'cancelada')
                                    <span class="badge bg-danger w-100 py-2 shadow-sm">Cancelada</span>
                                @else
                                    <span class="badge bg-warning text-dark w-100 py-2 shadow-sm">Pendiente</span>
                                @endif
                            </td>

                            {{-- COMISIÓN --}}
                            <td class="text-end fw-bold">
                                @if($reserva->hotel && $estado != 'cancelada')
                                    <span class="text-success">+ {{ number_format($reserva->hotel->Comision, 2) }} €</span>
                                @else
                                    <span class="text-muted opacity-50">-</span>
                                @endif
                            </td>

                            {{-- ACCIONES --}}
                            <td class="text-center pe-4">
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('admin.reservas.edit', $reserva->id_reserva) }}" 
                                       class="btn btn-primary btn-sm px-3" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($estado != 'cancelada')
                                        <form action="{{ route('admin.reservas.destroy', $reserva->id_reserva) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Deseas anular esta reserva?');" 
                                              class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm px-3" title="Anular">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-light btn-sm px-3 text-muted border" disabled title="Ya anulada">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">No se encontraron reservas con los filtros actuales.</p>
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