@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1><b>Crear Producto</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            @livewire('product-wizard')
        </div>
    </div>
@stop

@section('css')
    <style>
        html,
        body {
            height: 100%;
            overflow-x: hidden;
        }

        .content-wrapper {
            background-color: #f1f1f1;
        }

        .card-body {
            background-color: #ffffff;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
@stop
