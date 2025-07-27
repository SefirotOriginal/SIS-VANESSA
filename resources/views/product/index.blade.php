@extends('adminlte::page')

@section('title', 'Consultar product')

@section('content_header')
    <h1><b>Consulta de product</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row mt-4">
                <a href="{{route('products.create')}}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Crear product
                </a>
                <div class="table-responsive">
                    <table id="productsTable" class="table table-striped text-center" style="width:100%">
                        <thead class="custom-header">
                            <tr>
                                <th>Nombre</th>
                                <th>Código de barras</th>
                                <th>Categoria</th>
                                <th>Laboratorio</th>
                                <th>Uso</th>
                                <th>Contenido</th>
                                <th>Formula</th>
                                <th>Stock</th>
                                <th>Descripcion</th>
                                <th>Presentacion</th>
                                <th>Fecha de expiracion</th>
                                <th>Precio de compra</th>
                                <th>Precio de venta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            @foreach($products as $product)

                            <tr>
                                {{-- <tr>
                            <td>product 1</td>
                            <td>1234567890123</td>
                            <td>Categoria 1</td>
                            <td>Laboratorio 1</td>
                            <td>Uso 1</td>
                            <td>Contenido 1</td>
                            <td>Formula 1</td>
                            <td class="text-center">100</td>
                            <td>Descripción del product 1</td>
                            <td>Presentación 1</td>
                            <td>2023-12-31</td>
                            <td class="text-center">$10.00</td>
                            <td class="text-center">$15.00</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm" title="Editar">
                                    <i class="fas fa-pen
                        @foreach ($product as $product)
                        <tr>
                            {{-- <td>{{ $product->name }}</td>
                            <td class="text-center">{{ $product->barCode }}</td>
                            <td>{{ $product->description }}</td>
                            <td class="text-center">{{ $product->currentStock }}</td>
                            <td class="text-center">${{ number_format($product->purchasePrice, 2) }}</td>
                            <td class="text-center">${{ number_format($product->salePrice, 2) }}</td>
                            <td>{{ $product->category ? $product->category->name : 'Sin categoría' }}</td>
                            <td>{{ $product->laboratory ? $product->laboratory->name : 'Sin laboratorio' }}</td>
                            <td>{{ $product->batch ? 'Lote #' . $product->batch->batchNumber : 'Sin lote' }}</td>
                            <td>
                                {{ $product->batch && $product->batch->expirationDate
                                    ? \Carbon\Carbon::parse($product->batch->expirationDate)->format('d/m/Y')
                                    : 'Sin fecha' }}
                            </td>
                            <td>
                                <a href="{{ route('product.edicion', $product->id) }}" class="btn btn-info btn-sm" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="formEliminar{{ $product->id }}" action="{{ route('product.eliminar', $product->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" title="Eliminar" onclick="confirmarEliminacion({{ $product->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td> --}}
                                <td>{{ $product->name }}</td>
                                <td class="text-center">{{ $product->bar_code }}</td>
                                <td>{{ $product->category ? $product->category->name : 'Sin categoría' }}</td>
                                <td>{{ $product->laboratory ? $product->laboratory->name : 'Sin laboratorio' }}</td>
                                <td>{{ $product->use }}</td>
                                <td>{{ $product->content }}</td>
                                <td>{{ $product->formula }}</td>
                                <td class="text-center">{{ $product->currentStock }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->presentation }}</td>
                                <td>
                                    {{ $product->batch && $product->batch->expirationDate
                                        ? \Carbon\Carbon::parse($product->batch->expirationDate)->format('d/m/Y')
                                        : 'Sin fecha' }}
                                </td>
                                <td class="text-center">${{ number_format($product->purchasePrice, 2) }}</td>
                                <td class="text-center">${{ number_format($product->salePrice, 2) }}</td>
                                <td>
                                    <a href="{{ route('product.edicion', $product->id) }}" class="btn btn-info btn-sm"
                                        title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form id="formEliminar{{ $product->id }}"
                                        action="{{ route('product.eliminar', $product->id) }}" method="POST"
                                        style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" title="Eliminar"
                                            onclick="confirmarEliminacion({{ $product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
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
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        .content-wrapper {
            background-color: #f1f1f1;
        }

        .card-body {
            background-color: #ffffff;
        }

        .custom-header {
            background-color: #0077B6;
            color: white;
        }

        .custom-header th {
            white-space: nowrap;
        }

        .card-body {
            padding-top: 0;
            padding-bottom: 1rem;
        }
    </style>

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                layout: {
                    topStart: 'search',
                    topEnd: null,
                    bottomStart: [{
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
        });
    </script>

    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar' + id).submit();
                }
            });
        }
    </script>
@stop
