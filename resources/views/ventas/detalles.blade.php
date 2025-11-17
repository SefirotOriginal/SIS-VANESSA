@extends('adminlte::page')

@section('title', 'Detalles de Venta')

@section('content_header')
    <h1><b>Detalles de venta: Folio #{{ $venta->referenceNumber }}</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form id="ventaForm">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" value="{{ $venta->created_at->format('Y-m-d') }}" readonly>
                </div>
                <div class="col-md-3">
                    <label for="hora" class="form-label">Hora</label>
                    <input type="time" class="form-control" id="hora" value="{{ $venta->created_at->format('H:i') }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="atendio" class="form-label">Atendió</label>
                    <input type="text" class="form-control" id="atendio" value="{{ $venta->usuario->name ?? 'N/A' }}" readonly>
                </div>
            </div>
        </form>

        <div class="row mt-4">
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
                            @foreach($venta->detalles as $detalle)
                            <tr>
                                <td>
                                    {{ $detalle->productPresentation->product->name ?? 'Producto no encontrado' }}
                                    ({{ $detalle->productPresentation->presentation->name ?? '' }})
                                </td>
                                <td>${{ number_format($detalle->price, 2) }}</td>
                                <td>{{ $detalle->quantity }}</td>
                                <td>${{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body">
                        <h5>Total: <strong id="totalPagar" class="text-success">${{ number_format($venta->amountTotal, 2) }}</strong></h5>
                        <h5>Cantidad: <strong id="totalPagar">${{ number_format($venta->amountPayment, 2) }}</strong></h5>
                        <h5 class="mt-3">Cambio: <strong id="cambio" class="text-warning">${{ number_format($venta->amountExchange, 2) }}</strong></h5>
                        
                        <button type="submit" class="btn btn-warning mt-4 w-100">
                            <i class="fas fa-receipt"></i> Imprimir
                        </button>

                        <a href="{{ route('ventas.devoluciones', $venta->id) }}" class="btn btn-danger mt-4 w-100">
                            <i class="fas fa-strikethrough"></i> Devolución
                        </a>

                        <a href="{{ route('ventas.consulta') }}" class="btn btn-secondary mt-2 w-100">
                            <i class="fas fa-arrow-left"></i> Volver
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
        .content-wrapper{ background-color:#f1f1f1; }
        .card-body{ background-color:#ffffff; }
        .custom-header { background-color: #0077B6; color: white; }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>
    <script>
        new DataTable('#ventaDetalles', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            paging: false,
            searching: false,
            info: false
        });
    </script>
@stop