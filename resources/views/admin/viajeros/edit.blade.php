@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Editar Viajero</h5>
                    <span class="badge bg-secondary">ID: {{ $viajero->id_viajero }}</span>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.viajeros.update', $viajero->id_viajero) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $viajero->nombre) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Primer Apellido</label>
                                <input type="text" class="form-control" name="apellido1" value="{{ old('apellido1', $viajero->apellido1) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Segundo Apellido</label>
                                <input type="text" class="form-control" name="apellido2" value="{{ old('apellido2', $viajero->apellido2) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $viajero->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">

                        <h6 class="text-primary fw-bold mb-3"><i class="fas fa-lock me-2"></i>Cambiar Contraseña</h6>
                        <div class="alert alert-light border small">
                            Deja este campo vacío si no quieres cambiar la contraseña actual del viajero.
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nueva Contraseña</label>
                            <input type="password" class="form-control" name="password" placeholder="********">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.viajeros.index') }}" class="btn btn-light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-warning text-white">Actualizar Viajero</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection