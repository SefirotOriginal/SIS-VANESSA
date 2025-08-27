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
                    <i class="fas fa-plus"></i> Crear producto
                </a>
                <div class="table-responsive">
                    <table id="productsTable" class="table table-striped text-center" style="width:100%">
                        <thead class="custom-header">
                            <tr>
                                <th>Nombre</th>
                                <th>Categoria</th>
                                <th>Laboratorio</th>
                                <th>Código de barras</th>
                                <th>Contenido</th>
                                <th>Formula</th>
                                <th>Numero de lote</th>
                                <th>Stock</th>
                                <th>Stock minimo</th>
                                <th>Stock maximo</th>
                                <th>Precio de compra</th>
                                <th>Precio de venta</th>
                                <th>Fecha de expiracion</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            @foreach($products as $product)

                            {{-- <tr>
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
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info btn-sm"
                                        title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form id="formEliminar{{ $product->id }}"
                                        action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" title="Eliminar"
                                            onclick="confirmarEliminacion({{ $product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr> --}}
                            @foreach($product->productPresentations as $presentation)
                                    @foreach($presentation->batches as $batch)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>{{ $product->laboratory->name ?? 'N/A' }}</td>
                                            <td>{{ $presentation->bar_code }}</td>
                                            <td>{{ $presentation->content }}</td>
                                            <td>{{ $presentation->formula }}</td>
                                            <td>{{ $batch->batch_number }}</td>
                                            <td>{{ $batch->stock }}</td>
                                            <td>{{ $batch->min_stock }}</td>
                                            <td>{{ $batch->max_stock }}</td>
                                            <td>${{ number_format($presentation->purchase_price, 2) }}</td>
                                            <td>${{ number_format($presentation->sale_price, 2) }}</td>
                                            <td>{{ $batch->expiration_date->format('d/m/Y') }}</td>
                                            <td>
                                                @if($batch->stock <= $batch->min_stock)
                                                    <span class="badge badge-danger">Crítico</span>
                                                @elseif($batch->stock <= $batch->min_stock * 1.5)
                                                    <span class="badge badge-warning">Bajo</span>
                                                @else
                                                    <span class="badge badge-success">Normal</span>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                @endforeach
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
