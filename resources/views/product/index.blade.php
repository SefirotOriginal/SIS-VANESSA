@extends('adminlte::page')

@section('title', 'Inventario de Productos')

@section('content_header')
    <h1><b>Inventario de Productos</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row mt-4">
                <a href="{{route('products.create')}}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Crear producto
                </a>
                <div class="table-responsive">
                    {{-- Se aplican tus clases originales a la tabla --}}
                    <table id="productsTable" class="table table-striped text-center" style="width:100%">
                        {{-- Se mantiene tu cabecera personalizada --}}
                        <thead class="custom-header">
                            <tr>
                                <th></th> {{-- Columna para el control --}}
                                <th>Nombre del Producto</th>
                                <th>Presentación</th>
                                <th>Laboratorio</th>
                                <th>Código de Barras</th>
                                <th>Precio Venta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productPresentations as $presentation)
                                {{--CONDICIÓN PARA IGNORAR REGISTROS HUÉRFANOS --}}
                                @if($presentation->product)
                                    <tr data-batches="{{ json_encode($presentation->batches) }}">
                                        <td class="dt-control"></td>
                                        <td>{{ $presentation->product->name }}</td>
                                        <td>{{ $presentation->presentation->name }}</td>
                                        <td>{{ $presentation->product->laboratory->name ?? 'N/A' }}</td>
                                        <td>{{ $presentation->bar_code }}</td>
                                        <td>${{ number_format($presentation->sale_price, 2) }}</td>
                                        <td>
                                            <a href="{{ route('products.edit', $presentation->product->id) }}" class="btn btn-warning btn-sm" title="Editar Producto">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $presentation->product->id) }}" method="POST" class="d-inline" id="formEliminar{{ $presentation->product->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" title="Eliminar Producto" onclick="confirmarEliminacion({{ $presentation->product->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Se mantiene tu CSS original --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
        html, body { height: 100%; }
        .content-wrapper { background-color: #f1f1f1; }
        .card-body { background-color: #ffffff; }
        .custom-header { background-color: #0077B6; color: white; }
        .custom-header th { white-space: nowrap; }
        .card-body { padding-top: 0; padding-bottom: 1rem; }
        
        /* Estilos para el ícono de expandir/colapsar */
        td.dt-control {
            background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.dt-hasChild td.dt-control {
            background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
        }
    </style>
@stop

@section('js')
    {{-- Scripts de jQuery, DataTables, etc. --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

    <script>
        // Función que crea el HTML para la tabla de lotes
        function format(batchesData) {
            if (!batchesData || batchesData.length === 0) {
                return '<div class="p-3 text-center">No hay lotes registrados para esta presentación.</div>';
            }
            let batchRows = batchesData.map(batch => {
                let statusBadge = batch.stock <= batch.min_stock ? '<span class="badge bg-danger">Crítico</span>' : (batch.stock <= batch.min_stock * 1.5 ? '<span class="badge bg-warning">Bajo</span>' : '<span class="badge bg-success">Normal</span>');
                let expDate = new Date(batch.expiration_date).toLocaleDateString('es-ES');
                return `<tr>
                            <td>${batch.batch_number}</td>
                            <td>${batch.stock}</td>
                            <td>${expDate}</td>
                            <td>${statusBadge}</td>
                        </tr>`;
            }).join('');
            return `<div class="p-3" style="background-color: #f8f9fa;">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr><th>N° Lote</th><th>Stock</th><th>Fecha Exp.</th><th>Estado</th></tr>
                            </thead>
                            <tbody>${batchRows}</tbody>
                        </table>
                    </div>`;
        }

        $(document).ready(function() {
            // Se mantiene tu configuración original de DataTables
            const table = $('#productsTable').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                layout: {
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

            // Listener para el clic en el botón de expandir
            $('#productsTable tbody').on('click', 'td.dt-control', function () {
                let tr = $(this).closest('tr');
                let row = table.row(tr);
                if (row.child.isShown()) {
                    row.child.hide();
                } else {
                    let batchesData = tr.data('batches');
                    row.child(format(batchesData)).show();
                }
            });
        });

        // Tu función para confirmar eliminación
        function confirmarEliminacion(id) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Se eliminará el producto y toda su información asociada. ¡Esta acción no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar' + id).submit();
                }
            });
        }

        // Script para la notificación toast de éxito
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    </script>
@stop