@extends('layouts.admin')

@section('title', 'Editar Hotel')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="m-0 font-weight-bold">Editar Hotel: {{ $hotel->usuario }}</h5>
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

                    <form action="{{ route('admin.hoteles.update', $hotel->id_hotel) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-3">
                            <label class="form-label">Nombre del Hotel (Usuario)</label>
                            <input type="text" name="usuario" class="form-control" value="{{ old('usuario', $hotel->usuario) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Zona</label>
                                <select name="id_zona" class="form-select" required>
                                    @foreach($zonas as $zona)
                                    <option value="{{ $zona->id_zona }}" {{ $hotel->id_zona == $zona->id_zona ? 'selected' : '' }}>
                                        {{ $zona->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Comisión por Reserva (€)</label>
                                <input type="number" name="comision" class="form-control" value="{{ old('comision', $hotel->Comision) }}" min="0">
                            </div>
                        </div>

                        <hr>
                        <div class="mb-3 bg-light p-3 rounded">
                            <label class="form-label fw-bold">Cambiar Contraseña (Opcional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.hoteles.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection