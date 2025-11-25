@extends('layouts.app')

@section('title', 'Acceso denegado')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">Acceso denegado</div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-warning">{{ session('error') }}</div>
                        @endif

                        <p>No tienes permisos para ver esta página.</p>

                        <a href="{{ url()->previous() ?? route('dashboard') }}" class="btn btn-primary">Volver</a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
