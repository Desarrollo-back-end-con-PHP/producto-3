@extends('layouts.admin')

@section('title', 'Alta de Nuevo Hotel')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-hotel me-2"></i>Alta de Nuevo Hotel</h5>
                </div>
                <div class="card-body p-4">

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.hoteles.store') }}" method="POST">
                        @csrf

                        {{-- NOMBRE COMERCIAL (Para mostrar en listados) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre del Hotel (Comercial)</label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre') }}" placeholder="Ej: Hotel Iberostar Alcudia" required>
                        </div>

                        {{-- USUARIO (Para Login) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Usuario (Login)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="usuario" class="form-control"
                                       value="{{ old('usuario') }}" placeholder="Ej: hotel_iberostar" required>
                            </div>
                            <div class="form-text">Este será el identificador para iniciar sesión. Sin espacios.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Zona</label>
                                <select name="id_zona" class="form-select" required>
                                    <option value="">Selecciona una zona...</option>
                                    @foreach($zonas as $zona)
                                    <option value="{{ $zona->id_zona }}" {{ old('id_zona') == $zona->id_zona ? 'selected' : '' }}>
                                        {{ $zona->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Comisión por Reserva (€)</label>
                                <div class="input-group">
                                    <input type="number" name="comision" class="form-control" value="{{ old('comision', 10) }}" min="0" step="0.01">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña de Acceso</label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="form-text">Mínimo 6 caracteres.</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.hoteles.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar Hotel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
