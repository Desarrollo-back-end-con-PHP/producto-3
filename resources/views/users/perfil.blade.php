@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user mb-1 me-2"></i>Mi Perfil de Usuario</h4>
                    </div>

                    <div class="card-body p-4">
                        @auth
                            {{-- Avatar --}}
                            <div class="row mb-4">
                                <div class="col-md-12 text-center mb-3">
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 100px;">
                                        <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- MENSJAE GENERAL DEL CONTROLADOR (Éxito o Error al guardar datos) --}}
                            @if (session('mensaje'))
                                @php
                                    $codigo = session('mensaje');

                                    $texto = \App\Helpers\ProfileMessageHelper::getText($codigo);
                                    $clase = \App\Helpers\ProfileMessageHelper::getClaseAlerta($codigo);

                                    if (empty($texto)) {
                                        $texto = $codigo;
                                    }
                                    if (empty($clase)) {
                                        $clase = 'alert-info';
                                    }
                                @endphp

                                <div class="alert {{ $clase }} alert-dismissible fade show" role="alert">
                                    <i class="fas fa-info-circle me-1"></i> {{ $texto }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- FORMULARIO DATOS PERSONALES --}}
                            <form action="{{ route('usuario.datos.update') }}" method="POST">
                                @csrf
                                <h5 class="mb-3 text-primary"><i class="fas fa-address-card me-2"></i>Datos Personales</h5>

                                <div class="row g-3">
                                    {{-- Nombre --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" class="form-control"
                                            value="{{ old('nombre', $usuario->nombre) }}" required>
                                    </div>

                                    {{-- Primer Apellido --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Primer Apellido <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="apellido1" class="form-control"
                                            value="{{ old('apellido1', $usuario->apellido1) }}" required>
                                    </div>

                                    {{-- Segundo Apellido --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Segundo Apellido</label>
                                        <input type="text" name="apellido2" class="form-control"
                                            value="{{ old('apellido2', $usuario->apellido2) }}">
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Correo Electrónico <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control bg-light"
                                            value="{{ old('email', $usuario->email) }}" required>
                                        <small class="text-muted">Este es tu identificador de acceso.</small>
                                    </div>

                                    {{-- Dirección --}}
                                    <div class="col-md-12">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" name="direccion" class="form-control"
                                            value="{{ old('direccion', $usuario->direccion) }}"
                                            placeholder="Ej: Calle Principal, 123">
                                    </div>

                                    {{-- CP, Ciudad, País --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Código Postal</label>
                                        <input type="text" name="codigoPostal" class="form-control"
                                            value="{{ old('codigoPostal', $usuario->codigoPostal) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Ciudad</label>
                                        <input type="text" name="ciudad" class="form-control"
                                            value="{{ old('ciudad', $usuario->ciudad) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">País</label>
                                        <input type="text" name="pais" class="form-control"
                                            value="{{ old('pais', $usuario->pais) }}">
                                    </div>

                                    {{-- Botón Guardar --}}
                                    <div class="col-12 text-end mt-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-save me-2"></i>Guardar Datos
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <hr class="my-5">

                            {{-- CAMBIO DE CONTRASEÑA --}}
                            <h5 class="mb-3 text-primary"><i class="fas fa-lock me-2"></i>Seguridad</h5>

                            <form action="{{ route('usuario.password.update') }}" method="POST">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-5">
                                        <label class="form-label">Nueva Contraseña</label>
                                        <input type="password" name="nueva_contrasena" class="form-control" required
                                            minlength="8" placeholder="Mínimo 8 caracteres">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Repetir Contraseña</label>
                                        <input type="password" name="nueva_contrasena_confirmation" class="form-control"
                                            required minlength="8" placeholder="Repite la contraseña">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-warning w-100 text-white fw-bold">
                                            Cambiar
                                        </button>
                                    </div>
                                </div>

                                @if (session('success_pass'))
                                    <div class="alert alert-success mt-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> {{ session('success_pass') }}
                                    </div>
                                @endif
                                @if ($errors->has('nueva_contrasena'))
                                    <div class="alert alert-danger mt-3 py-2">
                                        {{ $errors->first('nueva_contrasena') }}
                                    </div>
                                @endif
                            </form>

                            <hr class="my-5">

                            {{-- SECCIÓN MIS RESERVAS --}}
                            <h4 class="mb-4 text-primary"><i class="fas fa-suitcase-rolling me-2"></i>Mis Reservas</h4>

                            @if (isset($reservas) && $reservas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle shadow-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Localizador</th>
                                                <th>Fecha Entrada</th>
                                                <th>Origen</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reservas as $reserva)
                                                <tr>
                                                    <td class="fw-bold text-primary">{{ $reserva->localizador }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}
                                                        <br> <small class="text-muted">{{ $reserva->hora_entrada }}</small>
                                                    </td>
                                                    <td>
                                                        {{ $reserva->origen_vuelo_entrada ?? 'N/A' }}
                                                        @if ($reserva->numero_vuelo_entrada)
                                                            <br><small class="text-muted"><i class="fas fa-plane"></i>
                                                                {{ $reserva->numero_vuelo_entrada }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($reserva->status == 'confirmada')
                                                            <span class="badge bg-success">Confirmada</span>
                                                        @elseif($reserva->status == 'cancelada')
                                                            <span class="badge bg-danger">Cancelada</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning text-dark">{{ $reserva->status }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-light border text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 fs-5">Aún no tienes ninguna reserva registrada.</p>
                                    <a href="{{ route('reservas.create') }}" class="btn btn-outline-primary mt-3">¡Haz tu
                                        primera reserva ahora!</a>
                                </div>
                            @endif

                            <hr class="my-4">

                            {{-- BOTONES NAVEGACIÓN --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ url('/') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Volver al Inicio
                                </a>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        @else
                            {{-- VISTA INVITADOS --}}
                            <div class="text-center py-5">
                                <h3 class="text-muted">No has iniciado sesión</h3>
                                <div class="d-grid gap-2 d-sm-block mt-4">
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 gap-3">Iniciar
                                        Sesión</a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
