@extends('adminlte::page')

@section('title', 'Nueva Venta')

@section('content_header')
<h1><b>Registro de venta</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form id="formVenta" action="{{ route('ventas.crear') }}" method="POST">
        @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="producto" class="form-label">Producto</label>
                    <select class="form-control" id="productoSelect">
                        <option value="">-- Seleccionar producto --</option>

                        @foreach ($productos as $producto)
                            <option 
                                value="{{ $producto->id }}" 
                                data-codigo="{{ $producto->bar_code }}"  {{-- CORREGIDO --}}
                                data-nombre="{{ $producto->product->name }} ({{ $producto->presentation->name }})"
                                data-precio="{{ $producto->sale_price }}" {{-- CORREGIDO --}}
                                data-stock="{{ $producto->batches_sum_stock }}">
                                {{ $producto->product->name }} ({{ $producto->presentation->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" placeholder="Código" readonly>
                </div>
                <div class="col-md-2">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" placeholder="$0.00" readonly step="0.01">
                </div>
                <div class="col-md-2">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" placeholder="0" readonly>
                </div>
                <div class="col-md-2">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" min="1" value="1">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" id="btnAgregar">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table id="ventaCrear" class="table table-striped text-center" style="width:100%">
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
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="receiptType" class="form-label">Tipo de recibo:</label>
                                <select name="receiptType" id="receiptType" class="form-control">
                                    <option value="Efectivo" selected>Efectivo</option>
                                    <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <h5>Total a pagar: <strong id="totalPagar" class="text-success">$0.00</strong></h5>

                            <input type="hidden" name="amountTotal" id="amountTotalInput" value="0">

                            <div class="form-group mt-3">
                                <label for="cantidadRecibida">Cantidad recibida</label>
                                {{-- Este input es para el PAGO (Payment) --}}
                                <input type="number" class="form-control" name="amountPayment" id="cantidadRecibida" placeholder="Monto entregado" value="0" step="0.01">
                            </div>

                            <h5 class="mt-3">Cambio: <strong id="cambio" class="text-warning">$0.00</strong></h5>
                            <input type="hidden" name="amountExchange" id="amountExchangeInput" value="0">
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                            <button type="button" class="btn btn-success mt-4 w-100" id="btnGuardar">
                                <i class="fas fa-check"></i> Confirmar Venta
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
{{-- Tu CSS (sin cambios) --}}
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
{{-- Tu JS (el que ya tienes con la API de DataTables) va aquí. Pega el que te di en el paso anterior. --}}
{{-- (No lo incluyo de nuevo aquí para no hacer la respuesta tan larga, pero es el mismo que empieza con 'var ventaTable;') --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>

<script>
    // Variable global para la tabla, para poder acceder a ella desde todas las funciones
    var ventaTable; 

    $(document).ready(function() {
        
        // --- 1. INICIALIZACIÓN DE DATATABLES ---
        ventaTable = $('#ventaCrear').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            paging: false,
            searching: false,
            info: false,
            ordering: true 
        });

        // --- 2. LÓGICA DE AGREGAR PRODUCTO (USANDO API) ---
        $('#btnAgregar').click(function() {
            let productoSelect = $('#productoSelect');
            let productoId = productoSelect.val();
            let nombre = productoSelect.find('option:selected').data('nombre');
            let precio = parseFloat($('#precio').val());
            let cantidad = parseInt($('#cantidad').val());
            let stock = parseInt($('#stock').val());

            // Validaciones
            if (!productoId) {
                Swal.fire('Error', 'Selecciona un producto.', 'warning'); return;
            }
            if (isNaN(precio) || precio <= 0) {
                Swal.fire('Error', 'El producto seleccionado no tiene un precio válido.', 'warning'); return;
            }
            if (isNaN(cantidad) || cantidad <= 0) {
                Swal.fire('Error', 'Ingresa una cantidad válida.', 'warning'); return;
            }
            if (cantidad > stock) {
                Swal.fire('Stock insuficiente', `Solo quedan ${stock} unidades.`, 'warning'); return;
            }

            let subtotal = precio * cantidad;

            let productNameHTML = `<span data-id="${productoId}">${nombre}</span>`;
            let priceHTML = `$${precio.toFixed(2)}`;
            let subtotalHTML = `$${subtotal.toFixed(2)}`;
            let deleteButtonHTML = `<button type="button" class="btn btn-danger btn-sm btn-eliminar-fila">
                                        <i class="fas fa-trash"></i>
                                    </button>`;

            ventaTable.row.add([
                productNameHTML,
                priceHTML,
                cantidad,
                subtotalHTML,
                deleteButtonHTML
            ]).draw(); 

            actualizarTotal();
            limpiarCampos();
        });

        // --- 3. LÓGICA DE ELIMINAR FILA (USANDO API) ---
        $('#detalleVenta').on('click', '.btn-eliminar-fila', function() {
            ventaTable.row($(this).closest('tr')).remove().draw();
            actualizarTotal();
        });

        // --- 4. LÓGICA DE ACTUALIZAR TOTALES (USANDO API) ---
        window.actualizarTotal = function() {
            let total = 0;
            
            ventaTable.rows().data().each(function(rowData) {
                let subtotal = parseFloat(rowData[3].replace('$', '')) || 0;
                total += subtotal;
            });

            $('#totalPagar').text(`$${total.toFixed(2)}`);

            let recibido = parseFloat($('#cantidadRecibida').val()) || 0;
            let cambio = recibido - total;
            $('#cambio').text(`$${cambio.toFixed(2)}`);
        }

        $('#cantidadRecibida').on('input', window.actualizarTotal);

        function limpiarCampos() {
            $('#productoSelect').val('');
            $('#codigo').val('');
            $('#precio').val('');
            $('#stock').val('');
            $('#cantidad').val('1');
        }

        $('#productoSelect').on('change', function() {
            let selected = $(this).find(':selected');
            let precio = selected.data('precio');
            let codigo = selected.data('codigo');
            let stock = selected.data('stock');

            $('#precio').val(precio || '');
            $('#codigo').val(codigo || '');
            $('#stock').val(stock || '');
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formVenta');
        const btnGuardar = document.getElementById('btnGuardar');
        const totalPagarEl = document.getElementById('totalPagar');
        const cantidadRecibidaEl = document.getElementById('cantidadRecibida');
        const amountTotalInputEl = document.getElementById('amountTotalInput'); // ID Correcto
        const amountExchangeInputEl = document.getElementById('amountExchangeInput'); // ID Correcto

        if (!window.ventaTable) {
            setTimeout(initializeSaveButton, 100);
        } else {
            initializeSaveButton();
        }

        function initializeSaveButton() {
             if (!btnGuardar) return;
             
            btnGuardar.addEventListener('click', function () {
                let total = parseFloat(totalPagarEl.innerText.replace('$', '')) || 0;
                let cantidadRecibida = parseFloat(cantidadRecibidaEl.value) || 0;
                
                let filasVisibles = window.ventaTable ? window.ventaTable.rows().count() : 0;

                if (total === 0 || filasVisibles === 0) {
                    Swal.fire('Error', 'Debe agregar al menos un producto a la venta.', 'warning');
                    return;
                }
                if (cantidadRecibida < total) {
                    Swal.fire('Error', 'La cantidad recibida no puede ser menor que el total a pagar.', 'warning');
                    return;
                }
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                Swal.fire({
                    title: "¿Registrar la venta?",
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
            document.querySelectorAll('input[name^="productos["]').forEach(el => el.remove());
            let total = 0;

            if (window.ventaTable) {
                window.ventaTable.rows().data().each(function(rowData, index) {
                    
                    // [0] es <span data-id="123">Nombre</span>
                    // [1] es $10.00 (Precio)
                    // [2] es 2 (Cantidad)
                    // [3] es $20.00 (Subtotal)
                    
                    let productId = $(rowData[0]).data('id');
                    let price = parseFloat(rowData[1].replace('$', '')) || 0;
                    let quantity = parseInt(rowData[2]);
                    let subtotal = parseFloat(rowData[3].replace('$', '')) || 0;

                    total += subtotal;

                    // 1. El ID (El backend espera 'products.*.id')
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="products[${index}][id]" value="${productId}">`);
                    
                    // La Cantidad (El backend espera 'products.*.quantity')
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="products[${index}][quantity]" value="${quantity}">`);
                    
                    // El Precio (El backend espera 'products.*.price')
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="products[${index}][price]" value="${price}">`);

                    // NOTA: No se envía el subtotal porque VentasController lo calcula por sí mismo
                });
            }

            let cantidadRecibida = parseFloat(cantidadRecibidaEl.value) || 0;
            let cambio = cantidadRecibida - total;
            
            if (amountTotalInputEl) amountTotalInputEl.value = total.toFixed(2);
            if (amountExchangeInputEl) amountExchangeInputEl.value = cambio.toFixed(2);
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