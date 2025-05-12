@extends('adminlte::page')

@section('title', 'Crear Reporte')

@section('content_header')
    <h1><b>Generar nuevo reporte</b></h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card shadow w-100">
        <div class="card-body">

        <div class="row">
    {{-- Fecha inicial --}}
    <div class="col-md-4 mb-3">
        <label for="fecha_inicio" class="form-label"><b>Fecha inicial</b></label>
        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
    </div>

    {{-- Fecha final --}}
    <div class="col-md-4 mb-3">
        <label for="fecha_final" class="form-label"><b>Fecha final</b></label>
        <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
    </div>

    {{-- Tipo de reporte --}}
    <div class="col-md-4 mb-3">
        <label for="tipo_reporte" class="form-label"><b>Tipo de reporte</b></label>
        <select class="form-control" id="tipo_reporte" name="tipo_reporte" required>
            <option value="">-- Seleccionar --</option>
            <option value="ventas">Ventas</option>
            <option value="productos">Productos</option>
            <option value="miscelaneo">Misceláneo</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label for="descripcion" class="form-label"><b>Descripción del reporte</b></label>
    <textarea class="form-control" id="descripcion" name="descripcion" rows="5" placeholder="Agrega una descripción detallada si lo deseas..."></textarea>
</div>
<div class="d-flex justify-content-between">
    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Cancelar
    </a>
    <button onclick="alertaConfirmar()" type="button" class="btn btn-success">
        <i class="fas fa-file-alt"></i> Generar Reporte
    </button>
</div>
@stop

@section('css')
<style>
    html, body {
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script>
        function alertaConfirmar() {
            Swal.fire({
            title: "Generar el reporte?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Generar",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("¡Reporte generado!", "", "success");
                // Redirige a la ruta deseada
                window.location.href = 'consultas';
            }
            });
        }
    </script>
@stop