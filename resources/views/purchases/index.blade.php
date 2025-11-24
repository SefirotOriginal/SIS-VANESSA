@extends('adminlte::page')

@section('title', 'Consultar Compras')

@section('content_header')
    <h1><b>Consulta de compras</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="row mt-4">
            {{-- Botón Crear --}}
            <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-cart-plus"></i> Registrar Compra
            </a>

            <div class="table-responsive">
                <table id="purchasesTable" class="table table-striped table-hover text-center" style="width:100%">
                    <thead class="custom-header">
                        <tr>
                            <th></th> {{-- Columna para el botón de expandir --}}
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Proveedor</th>
                            <th>Tipo</th>
                            <th>Total</th>
                            <th>Usuario</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                        {{-- Se guardan los detalles en un atributo data-details para usarlos en JS --}}
                        <tr data-details="{{ json_encode($purchase->details) }}">
                            <td class="dt-control"></td>
                            <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                            <td><span>{{ $purchase->reference_number }}</span></td>
                            <td>{{ $purchase->provider->companyName ?? 'N/A' }}</td>
                            <td>{{ $purchase->receipt_type }}</td>
                            <td class="text-success fw-bold">${{ number_format($purchase->amountTotal, 2) }}</td>
                            <td>{{ $purchase->user->name ?? 'N/A' }}</td>

                            <td>
                                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline" id="formEliminar{{ $purchase->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" title="Eliminar Compra" onclick="confirmarEliminacion({{ $purchase->id }})">
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
    .content-wrapper { background-color: #f1f1f1; }
    .card-body { background-color: #ffffff; }
    .custom-header { background-color: #0077B6; color: white; }
    .custom-header th { white-space: nowrap; }
    .card-body { padding-top: 0; padding-bottom: 1rem; }

    /* Estilos para el botón de expandir */
    td.dt-control {
        cursor: pointer;
        background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
    }
    tr.shown td.dt-control {
        background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
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
    // Detalles de la compra
    function format(details) {
        if (!details || details.length === 0) {
            return '<div class="p-3 text-center text-muted">No hay detalles disponibles para esta compra.</div>';
        }

        let rows = details.map(item => {
            let prodName = item.product ? item.product.name : 'Producto desconocido';
            let presentation = (item.product_presentation && item.product_presentation.presentation) 
                               ? item.product_presentation.presentation.name 
                               : '-';
            let batch = item.batch ? item.batch.batch_number : 'S/L';
            let quantity = item.stock; 
            let cost = parseFloat(item.purchase_price).toFixed(2);
            let subtotal = parseFloat(item.amount_total).toFixed(2);

            return `
                <tr>
                    <td class="text-start fw-bold text-primary">${prodName}</td>
                    <td>${presentation}</td>
                    <td><span>${batch}</span></td>
                    <td>$${cost}</td>
                    <td class="fw-bold">${quantity}</td>
                    <td>$${subtotal}</td>
                </tr>
            `;
        }).join('');

        // Se regresa la tabla completa
        return `
            <div class="p-3" style="background-color: #f8f9fa; border-left: 4px solid #0077B6;">
                <table class="table table-sm table-bordered table-hover" style="background-color: white;">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Presentación</th>
                            <th>Lote</th>
                            <th>Costo Unit.</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>
            </div>
        `;
    }

    $(document).ready(function () {
        var table = $('#purchasesTable').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            order: [[1, 'desc']], 
            layout: {
                topStart: 'search',
                topEnd: null,
                bottomStart: [
                    { paging: { div: { className: 'layout-start', text: 'top2 A' } } },
                    { info: { div: { className: 'layout-full', text: 'top2 B' } } }
                ],
                bottomEnd: null
            },

            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                    width: '30px'
                },
                { data: 'fecha' },
                { data: 'referencia' },
                { data: 'proveedor' },
                { data: 'tipo' },
                { data: 'total' },
                { data: 'usuario' },
                { data: 'opciones', orderable: false }
            ]
        });

        $('#purchasesTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                var detailsData = tr.data('details');
                row.child(format(detailsData)).show();
                tr.addClass('shown');
            }
        });
    });

    function confirmarEliminacion(id) {
        Swal.fire({
            title: "¿Eliminar esta compra?",
            text: "Esta acción eliminará el registro de la compra. El stock se revertirá automáticamente.",
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
</script>

<script>
@if (session('success'))
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: 'success',
      title: '{{ session('success') }}'
    });
@endif
</script>
@stop