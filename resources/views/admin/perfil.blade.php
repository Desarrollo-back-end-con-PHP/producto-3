@extends('layouts.admin')

@section('title', 'Mi Perfil de Administrador')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Mensajes de Éxito/Error --}}
            @if(session('mensaje'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('mensaje') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Perfil de Administrador</h5>
                </div>
                <div class="card-body p-4">
                    {{-- Formulario de Datos --}}
                    <form action="{{ route('usuario.datos.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Primer Apellido</label>
                                <input type="text" name="apellido1" class="form-control" value="{{ old('apellido1', $usuario->apellido1) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Corporativo</label>
                            <input type="email" name="email" class="form-control bg-light" value="{{ $usuario->email }}" readonly>
                            <small class="text-muted">Los correos @islatransfers.com tienen privilegios de administrador.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-dark w-100">
                            <i class="fas fa-save me-2"></i>Actualizar Mis Datos
                        </button>
                    </form>

                    <hr class="my-4">

                    {{-- Cambio de Contraseña --}}
                    <h5 class="text-danger mb-3"><i class="fas fa-key me-2"></i>Seguridad de Acceso</h5>
                    <form action="{{ route('usuario.password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="nueva_contrasena" class="form-control" placeholder="Mínimo 8 caracteres" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="nueva_contrasena_confirmation" class="form-control" placeholder="Repite la contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-lock me-2"></i>Actualizar Clave Maestra
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection