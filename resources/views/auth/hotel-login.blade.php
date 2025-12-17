@extends('layouts.app')
@section('title', 'Acceso Corporativo - Hoteles') @section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-lg border-0 rounded-lg mt-5">

                <div class="card-header bg-dark text-white">
                    <h3 class="text-center fw-light my-4">
                        <i class="fas fa-building me-2"></i> Panel Corporativo
                    </h3>
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

                    <form action="{{ route('hotel.login.post') }}" method="POST">
                        @csrf <div class="form-floating mb-3">
                            <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Usuario del Hotel" value="{{ old('usuario') }}" required autofocus />
                            <label for="usuario">Usuario del Hotel</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña" required />
                            <label for="password">Contraseña</label>
                        </div>

                        <div class="d-grid gap-2 mt-4 mb-0">
                            <button type="submit" class="btn btn-dark btn-lg">Acceder al Panel</button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center py-3">
                    <div class="small">
                        <a href="{{ route('login') }}">¿Eres un viajero particular? Acceso Clientes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
