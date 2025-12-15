@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user mb-1 me-2"></i>Mi Perfil de Usuario</h4>
                </div>
                
                <div class="card-body p-4">
                    {{-- Comprobamos si hay usuario logueado (Guard por defecto web) --}}
                    @auth
                        <div class="row mb-4">
                            <div class="col-md-12 text-center mb-3">
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                </div>
                            </div>
                        </div>

                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre</label>
                                {{-- Intentamos obtener 'name' o 'nombre' según tu base de datos --}}
                                <input type="text" class="form-control" value="{{ Auth::user()->name ?? Auth::user()->nombre ?? 'Usuario' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Correo Electrónico</label>
                                <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                            </div>
                            
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-1"></i> 
                                Sesión iniciada correctamente.
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Volver al Inicio
                            </a>
                            
                            {{-- Botón de Cerrar Sesión --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>

                    @else
                        {{-- Si entran aquí sin estar logueados --}}
                        <div class="text-center py-5">
                            <h3 class="text-muted">No has iniciado sesión</h3>
                            <p class="mb-4">Para ver tu perfil de viajero, debes identificarte.</p>
                            <div class="d-grid gap-2 d-sm-block">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 gap-3">Iniciar Sesión</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Registrarse</a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection