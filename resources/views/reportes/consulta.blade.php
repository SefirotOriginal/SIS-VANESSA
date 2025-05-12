@extends('adminlte::page')

@section('title', 'Consultar Reportes')

@section('content_header')
<h1><b>Consulta de reportes</b></h1>
@stop

@section('content')
<div class="card shadow">
<div class="card-body">
    <div class="row mt-4">
        <!-- Tabla de detalles de reportes -->
         <div class="table-responsive">
            <table id="reporteConsulta" class="table table-striped" style="width:100%">
                <thead class="custom-header">
                    <tr>
                        <th>Código</th>
                        <th>Fecha inicial</th>
                        <th>Fecha final</th>
                        <th>Tipo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="detalleReporte">
                    <!-- Aquí irían las filas dinámicas -->
                    <tr>
                        <td>0001</td>
                        <td>2025-01-01</td>
                        <td>2025-02-01</td>
                        <td>Ventas</td>
                        <td>
                            <a href="detalles" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="alertaBorrar()" type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>       
                    </tr>
                    <tr>
                        <td>0002</td>
                        <td>2025-03-01</td>
                        <td>2025-04-01</td>
                        <td>Ventas</td>
                        <td>
                            <a href="detalles" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="alertaBorrar()" type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
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
<style>
    .custom-header th {
        white-space: nowrap; /* Evita que el texto se divida */
    }
    .card-body {
        padding-top: 0; /* Elimina el padding superior */
        padding-bottom: 1rem; /* Ajusta el padding inferior si es necesario */
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
        new DataTable('#reporteConsulta', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            layout: {
                top2End: function () {
                    // Create a div for the toolbar
                    let toolbar = document.createElement('div');
                    toolbar.className = 'd-flex align-items-center mb-3';
                    // Create the "Create Product" button
                    let createButton = document.createElement('a');
                    createButton.href = "creacion";
                    createButton.className = 'btn btn-primary ml-2';
                    createButton.innerHTML = '<i class="fas fa-plus-circle me-2"></i>Crear reporte';
                    // Append the button to the toolbar
                    toolbar.appendChild(createButton);
                    return toolbar; // Return the custom toolbar
                },
                topStart: 'search',
                topEnd: null,
                bottomStart: [
                    {
                        paging: {
                            div: {
                                className: 'layout-start',
                                text: 'top2 A'
                            }
                        }
                    },
                    {
                        info: {
                            div: {
                                className: 'layout-full',
                                text: 'top2 B'
                            }
                        }
                    }
                ],
                bottomEnd: null
            }
        });
    </script>
    <script>
        function alertaBorrar() {
            Swal.fire({
            title: "¿Estás seguro?",
            text: "¡Esta acción no se podrá revertir!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, ¡Eliminar!",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: "¡Eliminado!",
                text: "El reporte se ha eliminado de la tabla.",
                icon: "success"
                });
            }
            });
        }
    </script>
@stop