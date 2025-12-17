@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center fw-light my-4">Crear Cuenta</h3>
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

                    <form action="{{ route('registro.store') }}" method="POST">
                        @csrf

                        <div class="form-floating mb-3">
                            <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Nombre" value="{{ old('nombre') }}" required />
                            <label for="nombre">Nombre</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input class="form-control" id="apellido1" name="apellido1" type="text" placeholder="Primer Apellido" value="{{ old('apellido1') }}" required />
                            <label for="apellido1">Primer Apellido</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" value="{{ old('email') }}" required />
                            <label for="email">Correo Electrónico</label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control" id="password" name="password" type="password" placeholder="Pass" required />
                                    <label for="password">Contraseña</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm" required />
                                    <label for="password_confirmation">Confirmar</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4 mb-0">
                            <button type="submit" class="btn btn-primary btn-lg">Crear Cuenta</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small"><a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection