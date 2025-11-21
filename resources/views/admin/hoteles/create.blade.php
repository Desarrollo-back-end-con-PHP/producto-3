@extends('layouts.app')

@section('title', 'Alta de Nuevo Hotel')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0 font-weight-bold">Alta de Nuevo Hotel</h5>
                </div>
                <div class="card-body">

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

                        <div class="mb-3">
                            <label class="form-label">Nombre del Hotel (Usuario)</label>
                            <input type="text" name="usuario" class="form-control" value="{{ old('usuario') }}" required>
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
                                <input type="number" name="comision" class="form-control" value="{{ old('comision', 10) }}" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña de Acceso</label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="form-text">Mínimo 6 caracteres.</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.hoteles.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Hotel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection