@extends('adminlte::page')

@section('title', 'Detalles de Venta')

@section('content_header')
<h1><b>Detalles de venta</b></h1>
@stop

@section('content')
{{-- Div contenedor de campos: código, producto, precio, stock y cantidad --}}
<div class="card shadow">
    <div class="card-body">
        <form id="ventaForm">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" value="2025-04-25" readonly>
                </div>
                <div class="col-md-3">
                    <label for="hora" class="form-label">Hora</label>
                    <input type="time" class="form-control" id="hora" value="14:30" readonly>
                </div>
                <div class="col-md-4">
                    <label for="atendio" class="form-label">Atendió</label>
                    <input type="text" class="form-control" id="atendio" value="Monserrat Padilla" readonly>
                </div>
            </div>
        </form>

        {{-- Sección dividida en 2 columnas horizontales --}}
        <div class="row mt-4">
            <!-- Tabla de detalles de venta -->
            <div class="col-md-9">
                <div class="table-responsive">
                    <table id="ventaDetalles" class="table table-striped" style="width:100%">
                        <thead class="custom-header">
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detalleVenta">
                            {{-- Aquí van los productos añadidos --}}
                            <!-- Aquí irían las filas dinámicas -->
                            <tr>
                                <td>Paracetamol 450mg</td>
                                <td>$30.00</td>
                                <td>1</td>
                                <td>$30.00</td>
                            </tr>
                            <tr>
                                <td>Botella de Agua Ciel 600ml</td>
                                <td>$15.00</td>
                                <td>1</td>
                                <td>$15.00</td>
                            </tr>
                            <!-- Fin de ejemplo -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Panel de totales y confirmación -->
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body">
                        <h5>Total: <strong id="totalPagar" class="text-success">$45.00</strong></h5>
                        <h5>Cantidad: <strong id="totalPagar">$50.00</strong></h5>
                        <h5 class="mt-3">Cambio: <strong id="cambio" class="text-warning">$5.00</strong></h5>
                        <button type="submit" class="btn btn-warning mt-4 w-100">
                            <i class="fas fa-receipt"></i> Imprimir
                        </button>
                        <a href="{{ route('ventas.devoluciones') }}" class="btn btn-danger mt-4 w-100">
                            <i class="fas fa-strikethrough"></i> Devolución
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
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
        new DataTable('#ventaDetalles', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            paging: false,          // Oculta la paginación
            searching: false,       // Oculta la barra de búsqueda
            info: false             // Oculta el texto "Mostrando registros del 1 al 5 de un total de 30"
        });
    </script>
@stop
