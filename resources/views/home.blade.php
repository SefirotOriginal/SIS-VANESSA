@extends('adminlte::page')

@section('title', 'Venta')

@section('content_header')
<h1><b>Ventas</b></h1>
@stop

@section('content')
{{-- Div contenedor de campos: código, producto, precio, stock y cantidad --}}
<div class="card shadow">
    <div class="card-body">
        <form id="ventaForm">
            <div class="row g-3">
                <div class="col-md-2">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" placeholder="Código" readonly>
                </div>
                <div class="col-md-3">
                    <label for="producto" class="form-label">Producto</label>
                    <input type="text" class="form-control" id="producto" placeholder="Producto" readonly>
                </div>
                <div class="col-md-2">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" placeholder="$0.00" readonly>
                </div>
                <div class="col-md-2">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" placeholder="Disponible" readonly>
                </div>
                <div class="col-md-2">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" placeholder="Cantidad">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" id="btnAgregar">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </form>

        {{-- Sección dividida en 2 columnas horizontales --}}
        <div class="row mt-4">
            <!-- Tabla de detalles de venta -->
            <div class="col-md-9">
                <div class="table-responsive">
                    <table id="ventaCrear" class="table table-striped" style="width:100%">
                        <thead class="custom-header">
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Opciones</th>
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
                                <td>
                                    <button onclick="alertaBorrar()" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Botella de Agua Ciel 600ml</td>
                                <td>$15.00</td>
                                <td>1</td>
                                <td>$15.00</td>
                                <td>
                                    <button onclick="alertaBorrar()" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
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
                        <h5>Total a pagar: <strong id="totalPagar" class="text-success">$45.00</strong></h5>
                        <div class="form-group mt-3">
                            <label for="cantidadRecibida">Cantidad recibida</label>
                            <input type="number" class="form-control" id="cantidadRecibida" placeholder="Monto entregado" value="50">
                        </div>
                        <h5 class="mt-3">Cambio: <strong id="cambio" class="text-warning">$5.00</strong></h5>
                        <button onclick="alertaConfirmar()" type="submit" class="btn btn-success mt-4 w-100">
                            <i class="fas fa-check"></i> Confirmar Venta
                        </button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script>
        new DataTable('#ventaCrear', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            paging: false,          // Oculta la paginación
            searching: false,       // Oculta la barra de búsqueda
            info: false             // Oculta el texto "Mostrando registros del 1 al 5 de un total de 30"
        });
    </script>
    <script>
        function alertaConfirmar() {
            Swal.fire({
            title: "¿Registrar la venta?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Registrar",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("¡Venta registrada!", "", "success");
            }
            });
        }
        function alertaBorrar() {
            Swal.fire({
            title: "¿Eliminar producto?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, ¡Eliminar!",
            cancelButtonText: "Cancelar"
            });
        }
    </script>
@stop
