@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-1">Gestión de Viajeros</h1>
            <p class="text-muted mb-0">Listado de usuarios registrados en la plataforma.</p>
        </div>
        <a href="{{ route('admin.viajeros.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-2"></i>Nuevo Viajero
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase text-secondary text-xs font-weight-bolder">
                    <tr>
                        <th class="ps-4 py-3">Viajero</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Estado</th>
                        <th class="py-3">Registro</th>
                        <th class="text-end pe-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($viajeros as $viajero)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ strtoupper(substr($viajero->nombre, 0, 1) . substr($viajero->apellido1, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">
                                        {{ $viajero->nombre }} {{ $viajero->apellido1 }} {{ $viajero->apellido2 }}
                                    </div>
                                    <small class="text-muted">ID: #{{ $viajero->id_viajero }}</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="text-dark"><i class="far fa-envelope text-muted me-2"></i>{{ $viajero->email }}</span>
                        </td>

                        <td>
                            @if($viajero->status == 1)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Activo</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Inactivo</span>
                            @endif
                        </td>

                        <td>
                            <span class="text-secondary text-sm fw-bold">
                                {{ \Carbon\Carbon::parse($viajero->fecha_creacion)->format('d/m/Y') }}
                            </span>
                        </td>

                        <td class="text-end pe-4">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.viajeros.edit', $viajero->id_viajero) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                
                                <form action="{{ route('admin.viajeros.destroy', $viajero->id_viajero) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar a {{ $viajero->nombre }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay viajeros registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-end">
            {{ $viajeros->links() }}
        </div>
    </div>
</div>
@endsection