@extends('layouts.app')

@section('title', 'Inicio - Isla Transfers')

@section('content')

<div class="hero-section-with-image shadow" style="background-image: url('{{ asset('img/hero-image.webp') }}');">
    <div class="overlay"></div>

    <div class="container text-center text-white position-relative" style="z-index: 2; padding-top: 5rem;">
        <h1 class="display-3 fw-bold mb-4">Bienvenido a Isla Transfers</h1>
        <p class="fs-4 mb-5 col-md-8 mx-auto">Tu servicio de traslados en la isla. Puntual, fiable y sin complicaciones.</p>

        @auth('hotel')
        <a href="{{ route('hotel.reservas.create') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold">
            <i class="fas fa-calendar-plus me-2"></i> Nueva Reserva Corporativa
        </a>
        @else
        <a href=""{{ route('hotel.login') }}"" class="btn btn-light btn-lg px-5 py-3 fw-bold">
            <i class="fas fa-calendar-check me-2"></i> ¡Reserva ahora!
        </a>
        @endauth
    </div>
</div>

<div class="container px-4 py-5">
    <h2 class="pb-2 border-bottom text-center display-6 fw-bold mb-5">Nuestros Servicios</h2>
    <div class="row g-4 py-3 row-cols-1 row-cols-md-3">
        <div class="col">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-plane-arrival fa-4x text-primary"></i>
                </div>
                <div class="card-body p-0">
                    <h3 class="fs-4 fw-bold">Aeropuerto-Hotel</h3>
                    <p class="text-muted">Te recogemos y te llevamos sin esperas.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-plane-departure fa-4x text-primary"></i>
                </div>
                <div class="card-body p-0">
                    <h3 class="fs-4 fw-bold">Hotel-Aeropuerto</h3>
                    <p class="text-muted">Llegada puntual a tu vuelo de regreso.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-exchange-alt fa-4x text-primary"></i>
                </div>
                <div class="card-body p-0">
                    <h3 class="fs-4 fw-bold">Ida y Vuelta</h3>
                    <p class="text-muted">Reserva completa sin preocupaciones.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container px-4 py-5">
        <h2 class="text-center display-6 fw-bold mb-5">¿Cómo funciona?</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="feature-icon-simple bg-primary text-white mb-3 fs-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="fs-4 fw-bold">1. Reserva Online</h3>
                <p class="text-muted">Formulario rápido y sencillo.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon-simple bg-primary text-white mb-3 fs-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-car"></i>
                </div>
                <h3 class="fs-4 fw-bold">2. Te recogemos</h3>
                <p class="text-muted">Tu conductor te espera con un cartel.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon-simple bg-primary text-white mb-3 fs-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <h3 class="fs-4 fw-bold">3. Pago Seguro</h3>
                <p class="text-muted">Paga en efectivo o tarjeta al conductor.</p>
            </div>
        </div>
    </div>
</div>

@endsection