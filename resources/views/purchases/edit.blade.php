@extends('adminlte::page')

@section('title', 'Editar Compra')

@section('content_header')
    <h1><b>Editar Compra</b> <span class="text-muted text-sm">#{{ $purchase->reference_number }}</span></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form id="formCompra" action="{{ route('purchases.update', $purchase->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Datos generales de la compra --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="provider_id" class="form-label">Proveedor</label>
                    <select name="provider_id" id="provider_id" class="form-control" required>
                        <option value="" disabled>-- Seleccionar proveedor --</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" {{ $purchase->provider_id == $provider->id ? 'selected' : '' }}>
                                {{ $provider->companyName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="reference_number" class="form-label">N° Referencia / Factura</label>
                    <input type="text" name="reference_number" id="reference_number" class="form-control" value="{{ $purchase->reference_number }}" required>
                </div>
                <div class="col-md-4">
                    <label for="receipt_type" class="form-label">Tipo de Recibo</label>
                    <select name="receipt_type" id="receipt_type" class="form-control">
                        <option value="Factura" {{ $purchase->receipt_type == 'Factura' ? 'selected' : '' }}>Factura</option>
                        <option value="Nota" {{ $purchase->receipt_type == 'Nota' ? 'selected' : '' }}>Nota de Remisión</option>
                        <option value="Otro" {{ $purchase->receipt_type == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>

            <hr>

            {{-- Datos de productos de la compra --}}
            <div class="p-3 mb-3 rounded" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small">Producto</label>
                        <select id="productSelect" class="form-control">
                            <option value="">-- Buscar producto --</option>
                            @foreach($products as $item)
                                <option 
                                    value="{{ $item->id }}" 
                                    data-name="{{ $item->product->name }} ({{ $item->presentation->name }})"
                                    data-cost="{{ $item->purchase_price }}"
                                    data-batches='@json($item->batches)'>
                                    {{ $item->product->name }} ({{ $item->presentation->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Lote con Datalist --}}
                    <div class="col-md-2">
                        <label class="form-label small">Lote</label>
                        <input type="text" id="batchInput" list="batchList" class="form-control" placeholder="Lote" autocomplete="off">
                        <datalist id="batchList"></datalist>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Caducidad</label>
                        <input type="date" id="expirationInput" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Costo ($)</label>
                        <input type="number" id="costInput" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Cantidad</label>
                        <input type="number" id="qtyInput" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary w-100" id="btnAddRow">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

             {{-- Tabla de productos y Totales --}}
            <div class="row mt-4">
                <div class="col-md-9">
                    <div class="table-responsive">
                        {{-- ID crucial para los eventos --}}
                        <table id="purchaseTable" class="table table-striped text-center vertical-align-middle" style="width:100%">
                            <thead class="custom-header">
                                <tr>
                                    <th>Producto</th>
                                    <th>Lote</th>
                                    <th>Caducidad</th>
                                    <th>Costo</th>
                                    <th style="min-width: 100px;">Cantidad</th>
                                    <th>Subtotal</th>
                                    <th style="min-width: 140px;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody id="detalleCompra">
                                {{-- Se llenará con JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5>Total: <strong id="grandTotal" class="text-success">$0.00</strong></h5>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="button" class="btn btn-primary" id="btnSave">
                                    <i class="fas fa-sync-alt"></i> Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    table.dataTable tbody td { vertical-align: middle; }
    .btn-group-options { display: flex; justify-content: center; gap: 5px; }
    .qty-val { font-weight: bold; font-size: 1.1em; }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<script>
    var purchaseTable;
    var currentBatches = []; 
    var existingDetails = @json($purchase->details);

    // Se elimina el HTML, los signos de $ y comas para obtener un número limpio
    function parseNumber(val) {
        if (!val) return 0;
        if (typeof val === 'number') return val;
        let text = val.toString();
        text = text.replace(/<[^>]*>?/gm, '');  // Elimina tags HTML
        text = text.replace(/[^0-9.-]+/g, "");  // Elimina todo lo que no sea número, punto o guión
        return parseFloat(text) || 0;
    }

    function formatCurrency(amount) {
        return '$' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    $(document).ready(function() {
        purchaseTable = $('#purchaseTable').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            paging: false,
            searching: false,
            info: false,
            ordering: false 
        });

        // Se cargan los datos registrados de la compra
        if (existingDetails && existingDetails.length > 0) {
            existingDetails.forEach(item => {
                let id = item.product_presentation_id;
                let detailId = item.id; 
                
                let name = item.product.name;
                if(item.product_presentation && item.product_presentation.presentation){
                    name += ` (${item.product_presentation.presentation.name})`;
                }
                
                let batch = item.batch ? item.batch.batch_number : 'N/A';
                let expiration = item.batch ? item.batch.expiration_date : '';
                if(expiration && expiration.length > 10) expiration = expiration.substring(0, 10);

                let cost = parseFloat(item.purchase_price);
                let qty = parseInt(item.stock);
                let subtotal = cost * qty;

                agregarFilaTabla(id, detailId, name, batch, expiration, cost, qty, subtotal);
            });
            // Se calcula el total inicial con un pequeño retraso para asegurar DOM
            setTimeout(actualizarTotal, 200);
        }

        function agregarFilaTabla(id, detailId, name, batch, expiration, cost, qty, subtotal) {
            let productNameHTML = `<span data-id="${id}" data-detail-id="${detailId}">${name}</span>`;

            let costHTML = formatCurrency(cost);
            let qtyHTML = `<span class="qty-val">${qty}</span>`;
            let subtotalHTML = `<span class="row-subtotal">${formatCurrency(subtotal)}</span>`;
            
            let optionsHTML = `
                <div class="btn-group-options">
                    <button type="button" class="btn btn-warning btn-sm btn-decrementar"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-success btn-sm btn-incrementar"><i class="fas fa-plus"></i></button>
                    <button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fas fa-trash"></i></button>
                </div>`;

            purchaseTable.row.add([
                productNameHTML, batch, expiration, costHTML, qtyHTML, subtotalHTML, optionsHTML
            ]).draw(false);
        }

        function updateRowData(rowObject, dataArray, newQty, cost) {
            let newSubtotal = cost * newQty;
            dataArray[4] = `<span class="qty-val">${newQty}</span>`;
            dataArray[5] = `<span class="row-subtotal">${formatCurrency(newSubtotal)}</span>`;
            rowObject.data(dataArray).draw(false);
            actualizarTotal();
        }

        // Los eventos para los inputs
        $('#productSelect').on('change', function() {
            let selectedOption = $(this).find(':selected');
            let defaultCost = parseFloat(selectedOption.data('cost')) || 0;
            $('#costInput').val(defaultCost.toFixed(2));

            let batches = selectedOption.data('batches');
            currentBatches = batches || [];
            let datalist = $('#batchList');
            datalist.empty(); 
            if (currentBatches.length > 0) {
                currentBatches.forEach(batch => {
                    let option = $('<option>').attr('value', batch.batch_number).text(`Expira: ${batch.expiration_date}`);
                    datalist.append(option);
                });
            }
            $('#batchInput').val(''); $('#expirationInput').val('');
        });

        $('#batchInput').on('input', function() {
            let val = $(this).val().trim();
            let existingBatch = currentBatches.find(b => b.batch_number == val);
            if (existingBatch) {
                let rawDate = existingBatch.expiration_date;
                if (rawDate && rawDate.length > 10) rawDate = rawDate.substring(0, 10);
                $('#expirationInput').val(rawDate);
            }
        });

        // Se agregan filas por cada nuevo producto
        $('#btnAddRow').click(function() {
            const select = $('#productSelect');
            const id = select.val();
            const name = select.find(':selected').data('name');
            const batch = $('#batchInput').val().trim();
            const expiration = $('#expirationInput').val();
            const cost = parseFloat($('#costInput').val());
            const qtyToAdd = parseInt($('#qtyInput').val());

            if(!id) { Swal.fire('Error', 'Selecciona un producto', 'warning'); return; }
            if(!batch) { Swal.fire('Error', 'Escribe el lote', 'warning'); return; }
            if(!expiration) { Swal.fire('Error', 'Fecha caducidad requerida', 'warning'); return; }
            if(isNaN(cost) || cost < 0) { Swal.fire('Error', 'Costo inválido', 'warning'); return; }
            if(isNaN(qtyToAdd) || qtyToAdd <= 0) { Swal.fire('Error', 'Cantidad inválida', 'warning'); return; }

            let found = false;
            
            purchaseTable.rows().every(function() {
                let d = this.data();
                let currentId = $(d[0]).data('id'); 
                let currentBatch = d[1];
                let currentCost = parseNumber(d[3]);

                if (currentId == id && currentBatch === batch && Math.abs(currentCost - cost) < 0.01) {
                    let currentQty = parseNumber(d[4]);
                    updateRowData(this, d, currentQty + qtyToAdd, cost);
                    found = true; return false; 
                }
            });

            if (!found) {
                let subtotal = cost * qtyToAdd;
                agregarFilaTabla(id, '', name, batch, expiration, cost, qtyToAdd, subtotal);
                actualizarTotal();
            }
            
            $('#qtyInput').val('1'); $('#batchInput').val(''); $('#batchInput').focus();
        });


        // Botón para "Eliminar compra"
        $(document).off('click', '.btn-remove').on('click', '.btn-remove', function(e) {
            e.preventDefault();
            let tr = $(this).closest('tr');
            
            // Intentamos borrar de DataTables
            try {
                let table = $('#purchaseTable').DataTable();
                table.row(tr).remove().draw(false);
            } catch(err) {
                console.log("Error Datatables, forzando borrado visual");
            }

            tr.remove();
            actualizarTotal();
        });

        // Botón para "incrementar" la cantidad de un producto
        $(document).off('click', '.btn-incrementar').on('click', '.btn-incrementar', function(e) {
            e.preventDefault();
            let tr = $(this).closest('tr');
            let row = purchaseTable.row(tr);
            let d = row.data();
            
            let currentQty = parseNumber(d[4]);
            let cost = parseNumber(d[3]);

            updateRowData(row, d, currentQty + 1, cost);
        });

       // Botón para "reducir" la cantidad de un producto
        $(document).off('click', '.btn-decrementar').on('click', '.btn-decrementar', function(e) {
            e.preventDefault();
            let tr = $(this).closest('tr');
            let row = purchaseTable.row(tr);
            let d = row.data();
            
            let currentQty = parseNumber(d[4]);
            let cost = parseNumber(d[3]);

            if (currentQty > 1) {
                updateRowData(row, d, currentQty - 1, cost);
            }
        });

        // Se realiza el cálculo final de Total
        function actualizarTotal() {
            let total = 0;
            
            $('#purchaseTable tbody tr').each(function() {
                let subtotalElement = $(this).find('.row-subtotal');
                if (subtotalElement.length > 0) {
                    let valor = parseNumber(subtotalElement.text());
                    total += valor;
                }
            });

            $('#grandTotal').text(formatCurrency(total));
        }
    });

    // Se guardan los cambios realizados
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formCompra');
        const btnSave = document.getElementById('btnSave');

        if (!window.purchaseTable) { setTimeout(initializeSave, 100); } else { initializeSave(); }

        function initializeSave() {
            btnSave.addEventListener('click', function() {
                if (!form.checkValidity()) { form.reportValidity(); return; }
                
                // Verificación doble (visual y memoria)
                if ($('#purchaseTable tbody tr').length === 0) { 
                    Swal.fire('Error', 'Agrega productos', 'warning'); return; 
                }

                Swal.fire({
                    title: "¿Actualizar compra?",
                    text: "Se ajustará el stock según los cambios.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Sí, actualizar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        prepareAndSubmit();
                        form.submit();
                    }
                });
            });
        }
        
        function prepareAndSubmit() {
            // Se limpian los inputs previos
            document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());
            
            // Se itera sobre las filas visibles en el HTML y se normalizan sus datos
            $('#purchaseTable tbody tr').each(function(index) {
                let row = $(this);

                let spanProducto = row.find('td:eq(0) span'); 
                let id = spanProducto.data('id');
                let detailId = spanProducto.data('detail-id') || ''; 
                let batch = row.find('td:eq(1)').text().trim();
                let expiration = row.find('td:eq(2)').text().trim();
                let cost = parseNumber(row.find('td:eq(3)').text()); 
                let qty = parseNumber(row.find('td:eq(4)').text()); 

                // Si existe detailId, se envía para avisar a Laravel que es una modificación
                if(detailId) {
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][detail_id]" value="${detailId}">`);
                }
                
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][product_presentation_id]" value="${id}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][batch_number]" value="${batch}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][expiration_date]" value="${expiration}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][purchase_price]" value="${cost}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][quantity]" value="${qty}">`);
            });
        }
    });
</script>

<script>
    @if ($errors->any())
        Swal.fire({ title: 'Error', icon: 'error', html: `{!! implode('<br>', $errors->all()) !!}`, confirmButtonText: 'Cerrar' });
    @endif
</script>
@stop