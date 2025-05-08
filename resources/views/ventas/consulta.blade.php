@extends('adminlte::page')

@section('title', 'Consulta de ventas')

@section('content_header')
<h1><b>Consulta de ventas</b></h1>
@stop

@section('content')
<div class="card shadow">
<div class="card-body">
    {{-- Sección dividida en 2 columnas horizontales --}}
    <div class="row mt-4">
        <!-- Tabla de detalles de venta -->
         <div class="table-responsive">
            <table id="ventaConsulta" class="table table-striped" style="width:100%">
                <thead class="custom-header">
                    <tr>
                        <th>Atendió</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Total</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="detalleVenta">
                    <!-- Aquí irían las filas dinámicas -->
                    <tr>
                        <td>Monserrat Padilla</td>
                        <td>25-04-2025</td>
                        <td>Paracetamol 450g (x1), Botella de agua 600ml (x1)</td>
                        <td>$45.00</td>
                        <td>
                            <a href="{{ route('ventas.detalles') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- Fin de ejemplo -->
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .content-wrapper{
            background-color:#f1f1f1;
        }
        .card-body{
            background-color:#ffffff;
        }
        .custom-header {
            background-color: #0077B6;
            color: white;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script>
        new DataTable('#ventaConsulta', {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 5,
        lengthChange: false
        });
    </script>
@stop