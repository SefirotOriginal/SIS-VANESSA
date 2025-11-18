@extends('adminlte::page')

@section('title', 'Devolver Venta')

@section('content_header')
    <h1><b>Devolución de venta: Folio #{{ $sale->referenceNumber }}</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form id="formDevolucion" action="{{ route('sales.return.process', $sale->id) }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha"
                            value="{{ $sale->created_at->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="hora" class="form-label">Hora</label>
                        <input type="time" class="form-control" id="hora"
                            value="{{ $sale->created_at->format('H:i') }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="atendio" class="form-label">Atendió</label>
                        <input type="text" class="form-control" id="atendio" value="{{ $sale->user->name ?? 'N/A' }}"
                            readonly>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table id="ventaDevolucion" class="table table-striped" style="width:100%">
                                <thead class="custom-header">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="detalleVenta">
                                    @foreach ($sale->details as $detail)
                                        <tr>
                                            <td>
                                                {{ $detail->productPresentation->product->name ?? 'Producto no encontrado' }}
                                                ({{ $detail->productPresentation->presentation->name ?? '' }})
                                            </td>
                                            <td>${{ number_format($detail->price ?? ($detail->salePrice ?? 0), 2) }}</td>
                                            <td>{{ $detail->quantity ?? ($detail->quantityProduct ?? '') }}</td>
                                            <td>${{ number_format($detail->subtotal ?? ($detail->subTotal ?? 0), 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body">
                                <h5>Total a Devolver:</h5>
                                <h4 class="text-danger"><strong>${{ number_format($sale->amountTotal, 2) }}</strong></h4>

                                <button type="button" class="btn btn-danger mt-4 w-100" id="btnConfirmarDevolucion">
                                    <i class="fas fa-check"></i> Confirmar Devolución
                                </button>

                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary mt-2 w-100">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Tu CSS (sin cambios) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
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
    </style>
@stop

@section('js')
    {{-- Tus JS (actualizados) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#ventaDevolucion', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            paging: false,
            searching: false,
            info: false
        });
    </script>
    <script>
        // JS para la confirmación de SweetAlert
        document.getElementById('btnConfirmarDevolucion').addEventListener('click', function() {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción devolverá el stock al inventario y eliminará la venta. ¡No se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, confirmar Devolución",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si confirma, envía el formulario
                    document.getElementById('formDevolucion').submit();
                }
            });
        });

        // Script para la notificación toast (por si hay un error)
        @if ($errors->any())
            Swal.fire({
                title: 'Error al devolver',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
