@extends('adminlte::page')

@section('title', 'Registrar Compra')

@section('content_header')
    <h1><b>Registro de compra</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form id="formCompra" action="{{ route('purchases.store') }}" method="POST">
            @csrf
            
            {{-- Datos generales de la compra --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="provider_id" class="form-label">Proveedor</label>
                    <select name="provider_id" id="provider_id" class="form-control" required>
                        <option value="" selected disabled>-- Seleccionar proveedor --</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->companyName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="reference_number" class="form-label">N° Referencia / Factura</label>
                    <input type="number" name="reference_number" id="reference_number" class="form-control" placeholder="Ej. 998822" required>
                </div>
                <div class="col-md-4">
                    <label for="receipt_type" class="form-label">Tipo de Recibo</label>
                    <select name="receipt_type" id="receipt_type" class="form-control">
                        <option value="Factura">Factura</option>
                        <option value="Nota">Nota de Remisión</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>

            <hr>

            {{-- Datos de productos de la compra --}}
            <div class="p-3 mb-3 rounded" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                <div class="row g-3 align-items-end">
                    {{-- Producto --}}
                    <div class="col-md-3">
                        <label class="form-label small">Producto</label>
                        <select id="productSelect" class="form-control">
                            <option value="">-- Buscar producto --</option>
                            @foreach($products as $item)
                                <option 
                                    value="{{ $item->id }}" 
                                    data-name="{{ $item->product->name }} ({{ $item->presentation->name }})"
                                    {{-- Datos para automatización --}}
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
                                {{-- DataTables llenará esto --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5>Total: <strong id="grandTotal" class="text-success">$0.00</strong></h5>
                            <input type="hidden" id="totalHidden" value="0">
                            <button type="button" class="btn btn-success mt-4 w-100" id="btnSave">
                                <i class="fas fa-check"></i> Guardar Compra
                            </button>
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
    var currentBatches = [];    // Almacén temporal de lotes del producto actual

    $(document).ready(function() {
        purchaseTable = $('#purchaseTable').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            paging: false,
            searching: false,
            info: false,
            ordering: false 
        });

        // Se reconoce cuando se actualiza el producto seleccionado
        $('#productSelect').on('change', function() {
            let selectedOption = $(this).find(':selected');
            
            // Se actualiza el costo
            let defaultCost = parseFloat(selectedOption.data('cost')) || 0;
            $('#costInput').val(defaultCost.toFixed(2));

            // Se actualiza la Datalist de Lotes
            let batches = selectedOption.data('batches');
            currentBatches = batches || [];
            
            let datalist = $('#batchList');
            datalist.empty(); 

            if (currentBatches.length > 0) {
                currentBatches.forEach(batch => {
                    let option = $('<option>').attr('value', batch.batch_number).text(`Expira: ${batch.expiration_date} (Stock: ${batch.stock})`);
                    datalist.append(option);
                });
            }

            $('#batchInput').val('');
            $('#expirationInput').val('');
        });

        // Se actualiza la caducidad al escoger Lote
        $('#batchInput').on('input', function() {
            let val = $(this).val().trim(); // Quitamos espacios accidentales
            
            // Se busca si el Lote ingresado coincide con algún lote existente
            let existingBatch = currentBatches.find(b => b.batch_number == val);
            
            if (existingBatch) {
                let rawDate = existingBatch.expiration_date;
                // Si la fecha viene con hora (ej: 2025-05-20T00:00:00...), tomamos solo los primeros 10 chars
                if (rawDate && rawDate.length > 10) {
                    rawDate = rawDate.substring(0, 10);
                }

                $('#expirationInput').val(rawDate);
            }
        });

        // Lógica para el botón de "Agregar"
        $('#btnAddRow').click(function() {
            const select = $('#productSelect');
            const id = select.val();
            const name = select.find(':selected').data('name');
            
            const batch = $('#batchInput').val().trim();
            const expiration = $('#expirationInput').val();
            const cost = parseFloat($('#costInput').val());
            const qtyToAdd = parseInt($('#qtyInput').val());

            if(!id) { Swal.fire('Falta información', 'Selecciona un producto', 'warning'); return; }
            if(!batch) { Swal.fire('Falta información', 'Escribe el número de lote', 'warning'); return; }
            if(!expiration) { Swal.fire('Falta información', 'Selecciona la fecha de caducidad', 'warning'); return; }
            if(isNaN(cost) || cost <= 0) { Swal.fire('Error', 'Ingresa un costo válido', 'warning'); return; }
            if(isNaN(qtyToAdd) || qtyToAdd <= 0) { Swal.fire('Error', 'Ingresa una cantidad válida', 'warning'); return; }

            let found = false;
            
            purchaseTable.rows().every(function() {
                let d = this.data();
                let currentId = $(d[0]).data('id'); 
                let currentBatch = d[1]; 
                let currentExp = d[2];   
                let currentCost = parseFloat(d[3].replace('$', ''));

                if (currentId == id && currentBatch === batch && currentExp === expiration && currentCost === cost) {
                    let currentQty = parseInt($(d[4]).text());
                    let newQty = currentQty + qtyToAdd;
                    
                    updateRowData(this, d, newQty, cost);
                    found = true;
                    return false; 
                }
            });

            if (!found) {
                const subtotal = cost * qtyToAdd;
                
                let productNameHTML = `<span data-id="${id}">${name}</span>`;
                let costHTML = `$${cost.toFixed(2)}`;
                let qtyHTML = `<span class="qty-val font-weight-bold" style="font-size:1.1em">${qtyToAdd}</span>`;
                let subtotalHTML = `<span class="row-subtotal">$${subtotal.toFixed(2)}</span>`;
                let optionsHTML = `
                    <div class="btn-group-options">
                        <button type="button" class="btn btn-warning btn-sm btn-decrementar" title="-1"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-success btn-sm btn-incrementar" title="+1"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-remove" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </div>`;

                purchaseTable.row.add([
                    productNameHTML,
                    batch,
                    expiration,
                    costHTML,
                    qtyHTML,
                    subtotalHTML,
                    optionsHTML
                ]).draw();
            }

            $('#qtyInput').val('1');
            $('#batchInput').val(''); 
            $('#batchInput').focus();
            actualizarTotal();
        });

        $('#detalleCompra').on('click', '.btn-decrementar', function() {
            let row = purchaseTable.row($(this).closest('tr'));
            let d = row.data();
            let currentQty = parseInt($(d[4]).text());
            if (currentQty > 1) {
                let cost = parseFloat(d[3].replace('$', ''));
                updateRowData(row, d, currentQty - 1, cost);
            }
        });

        $('#detalleCompra').on('click', '.btn-incrementar', function() {
            let row = purchaseTable.row($(this).closest('tr'));
            let d = row.data();
            let currentQty = parseInt($(d[4]).text());
            let cost = parseFloat(d[3].replace('$', ''));
            updateRowData(row, d, currentQty + 1, cost);
        });

        $('#detalleCompra').on('click', '.btn-remove', function() {
            purchaseTable.row($(this).closest('tr')).remove().draw();
            actualizarTotal();
        });

        function updateRowData(rowObject, dataArray, newQty, cost) {
            let newSubtotal = cost * newQty;
            dataArray[4] = `<span class="qty-val font-weight-bold" style="font-size:1.1em">${newQty}</span>`;
            dataArray[5] = `<span class="row-subtotal">$${newSubtotal.toFixed(2)}</span>`;
            rowObject.data(dataArray).draw();
            actualizarTotal();
        }

        function actualizarTotal() {
            let total = 0;
            purchaseTable.rows().data().each(function(rowData) {
                let text = $(rowData[5]).text(); 
                let sub = parseFloat(text.replace('$', '')) || 0;
                total += sub;
            });
            $('#grandTotal').text(`$${total.toFixed(2)}`);
            $('#totalHidden').val(total);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formCompra');
        const btnSave = document.getElementById('btnSave');

        if (!window.purchaseTable) { setTimeout(initializeSave, 100); } else { initializeSave(); }

        function initializeSave() {
            btnSave.addEventListener('click', function() {
                if (!form.checkValidity()) { form.reportValidity(); return; }
                let filas = window.purchaseTable ? window.purchaseTable.rows().count() : 0;
                if (filas === 0) { Swal.fire('Error', 'Debes agregar al menos un producto', 'warning'); return; }

                Swal.fire({
                    title: "¿Registrar la compra?",
                    text: "Se actualizará el stock.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Registrar",
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
            document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());
            if (window.purchaseTable) {
                window.purchaseTable.rows().data().each(function(rowData, index) {
                    let id = $(rowData[0]).data('id'); 
                    let batch = rowData[1]; 
                    let expiration = rowData[2];
                    let cost = parseFloat(rowData[3].replace('$', ''));
                    let qty = parseInt($(rowData[4]).text()); 

                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][product_presentation_id]" value="${id}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][batch_number]" value="${batch}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][expiration_date]" value="${expiration}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][purchase_price]" value="${cost}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][quantity]" value="${qty}">`);
                });
            }
        }
    });
</script>

<script>
    @if ($errors->any())
        Swal.fire({
            title: 'Error al registrar',
            icon: 'error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'Cerrar'
        });
    @endif
</script>
@stop