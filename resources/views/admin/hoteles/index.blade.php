@extends('layouts.app')

@section('title', 'Gestión de Hoteles')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800"><i class="fas fa-hotel me-2"></i> Hoteles Colaboradores</h1>
        <a href="{{ route('admin.hoteles.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Hotel
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre (Usuario)</th>
                            <th>Zona</th>
                            <th>Comisión</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hoteles as $hotel)
                        <tr>
                            <td class="fw-bold">{{ $hotel->usuario }}</td>
                            <td>{{ $hotel->zona->descripcion ?? 'Sin Zona' }}</td>
                            <td>{{ $hotel->Comision }} €</td>
                            <td>
                                @if($hotel->status === 'activo')
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.hoteles.edit', $hotel->id_hotel) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.hoteles.destroy', $hotel->id_hotel) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres desactivar este hotel?');">
                                    @csrf
                                    @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection