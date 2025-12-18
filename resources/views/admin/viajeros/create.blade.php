@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Registrar Nuevo Viajero</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.viajeros.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Primer Apellido</label>
                                <input type="text" class="form-control" name="apellido1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Segundo Apellido (Opcional)</label>
                                <input type="text" class="form-control" name="apellido2">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required minlength="8">
                            <small class="text-muted">Mínimo 8 caracteres.</small>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.viajeros.index') }}" class="btn btn-light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection