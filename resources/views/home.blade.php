@extends('adminlte::page')

@section('title', 'Nueva Venta')

@section('content_header')
<h1><b>Registro de venta</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form id="formVenta" action="{{ route('sales.store') }}" method="POST">
        @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="producto" class="form-label">Producto</label>
                    <select class="form-control" id="productoSelect">
                        <option value="">-- Seleccionar producto --</option>

                        @foreach ($productos as $producto)
                            <option
                                value="{{ $producto->id }}"
                                data-codigo="{{ $producto->bar_code }}"
                                data-nombre="{{ $producto->product->name }} ({{ $producto->presentation->name }})"
                                data-precio="{{ $producto->sale_price }}"
                                data-stock="{{ $producto->batches_sum_stock }}">
                                {{ $producto->product->name }} ({{ $producto->presentation->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" placeholder="Código de barras" autocomplete="off">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>

<script>
    // Variable global para la tabla, para poder acceder a ella desde todas las funciones
    var ventaTable;

    $(document).ready(function() {
        ventaTable = $('#ventaCrear').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            paging: false,
            searching: false,
            info: false,
            ordering: true
        });

        // Botón para agregar un producto a la lista
        $('#btnAgregar').click(function() {
            let productoSelect = $('#productoSelect');
            let productoId = productoSelect.val();
            let nombre = productoSelect.find('option:selected').data('nombre');
            let precio = parseFloat($('#precio').val());
            let cantidadAAgregar = parseInt($('#cantidad').val()); // Cantidad del input
            let stock = parseInt($('#stock').val());

            // Validaciones
            if (!productoId) {
                Swal.fire('Error', 'Selecciona un producto.', 'warning'); return;
            }
            if (isNaN(cantidadAAgregar) || cantidadAAgregar <= 0) {
                Swal.fire('Error', 'Ingresa una cantidad válida.', 'warning'); return;
            }
            if (cantidadAAgregar > stock) {
                Swal.fire('Stock insuficiente', `Solo quedan ${stock} unidades.`, 'warning'); return;
            }

            // Se busca si el producto ya existe en la tabla
            let productoExistente = false;
            let filaExistente = null;

            ventaTable.rows().every(function() {
                let row = this;
                let rowData = row.data();

                // Se extrae el ID del producto de la primera celda
                let idEnFila = parseInt($(rowData[0]).data('id'));

                if (idEnFila == productoId) {
                    productoExistente = true;
                    filaExistente = row;
                    return;
                }
            });

            if (productoExistente) {
                let datosFila = filaExistente.data();
                let cantidadActual = parseInt(datosFila[2]);
                let maxStock = parseInt($(datosFila[0]).data('stock'));

                let nuevaCantidad = cantidadActual + cantidadAAgregar;

                // Se vuelve a validar el stock con la cantidad total
                if (nuevaCantidad > maxStock) {
                    Swal.fire('Stock insuficiente', `Solo quedan ${maxStock} unidades. Ya tienes ${cantidadActual} en el carrito.`, 'warning');
                    return;
                }

                let precioUnitario = parseFloat(datosFila[1].replace('$', ''));
                let nuevoSubtotal = precioUnitario * nuevaCantidad;

                datosFila[2] = nuevaCantidad;
                datosFila[3] = `$${nuevoSubtotal.toFixed(2)}`;

                // Se actualiza la fila en la tabla
                filaExistente.data(datosFila).draw();

            } else {
                let subtotal = precio * cantidadAAgregar;
                let productNameHTML = `<span data-id="${productoId}" data-stock="${stock}">${nombre}</span>`;
                let priceHTML = `$${precio.toFixed(2)}`;
                let subtotalHTML = `$${subtotal.toFixed(2)}`;
                let optionsHTML = `
                    <button type="button" class="btn btn-warning btn-sm btn-decrementar" style="margin-right: 5px;">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm btn-incrementar" style="margin-right: 5px;">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm btn-eliminar-fila">
                        <i class="fas fa-trash"></i>
                    </button>`;

                ventaTable.row.add([
                    productNameHTML,
                    priceHTML,
                    cantidadAAgregar,
                    subtotalHTML,
                    optionsHTML
                ]).draw();
            }
            actualizarTotal();
            limpiarCampos();
        });


        // Botón para eliminar un producto de la lista
        $('#detalleVenta').on('click', '.btn-eliminar-fila', function() {
            ventaTable.row($(this).closest('tr')).remove().draw();
            actualizarTotal();
        });

        // Listener para reducir la cantidad de un producto en la lista
        $('#detalleVenta').on('click', '.btn-decrementar', function() {
            let tr = $(this).closest('tr');
            let row = ventaTable.row(tr);
            let rowData = row.data();

            let cantidad = parseInt(rowData[2]);

            // No permite bajar de 1
            if (cantidad <= 1) {
                return;
            }

            cantidad--;

            let precio = parseFloat(rowData[1].replace('$', ''));
            let subtotal = precio * cantidad;

            // Actualizar los datos de la fila en DataTables
            rowData[2] = cantidad;
            rowData[3] = `$${subtotal.toFixed(2)}`;

            row.data(rowData).draw();
            actualizarTotal();
        });

        // Listener para aumentar la cantidad de un producto en la lista
        $('#detalleVenta').on('click', '.btn-incrementar', function() {
            let tr = $(this).closest('tr');
            let row = ventaTable.row(tr);
            let rowData = row.data();

            // Se obtiene el stock máximo del producto guardado en <span>
            let maxStock = parseInt($(rowData[0]).data('stock'));
            let cantidad = parseInt(rowData[2]);

            if (cantidad >= maxStock) {
                Swal.fire('Stock insuficiente', `Solo quedan ${maxStock} unidades.`, 'warning');
                return;
            }

            cantidad++;

            let precio = parseFloat(rowData[1].replace('$', ''));
            let subtotal = precio * cantidad;

            rowData[2] = cantidad;
            rowData[3] = `$${subtotal.toFixed(2)}`;

            row.data(rowData).draw();
            actualizarTotal();
        });


        // Actualización del precio total de la venta
        window.actualizarTotal = function() {
            let totalExacto = 0;

            // Cácluco del total exacto
            ventaTable.rows().data().each(function(rowData) {
                let subtotal = parseFloat(rowData[3].replace('$', '')) || 0;
                totalExacto += subtotal;
            });

            // Se redondea el total exacto a 2 decimales
            let tipoDeRecibo = $('#receiptType').val();
            let totalAPagar = totalExacto; // Por defecto, se vuelve el total exacto

            if (tipoDeRecibo === 'Efectivo') {
                // Se redondea al múltiplo de 50 centavos más cercano
                totalAPagar = Math.round(totalExacto * 2) / 2;
            }
            $('#totalPagar').text(`$${totalAPagar.toFixed(2)}`);

            let recibido = parseFloat($('#cantidadRecibida').val()) || 0;
            let cambio = recibido - totalAPagar;
            $('#cambio').text(`$${Math.max(0, cambio).toFixed(2)}`); // Evita cambio negativo
        }

        // Listeners
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

        // Se vuelve a calcular el total si cambia el tipo de recibo
        $('#receiptType').on('change', window.actualizarTotal);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formVenta');
        const btnGuardar = document.getElementById('btnGuardar');
        const totalPagarEl = document.getElementById('totalPagar');
        const cantidadRecibidaEl = document.getElementById('cantidadRecibida');
        const amountTotalInputEl = document.getElementById('amountTotalInput');
        const amountExchangeInputEl = document.getElementById('amountExchangeInput');

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
            // Limpia inputs antiguos
            document.querySelectorAll('input[name^="products["]').forEach(el => el.remove());

            let totalExacto = 0;

            if (window.ventaTable) {
                window.ventaTable.rows().data().each(function(rowData, index) {
                    let productId = $(rowData[0]).data('id');
                    let price = parseFloat(rowData[1].replace('$', '')) || 0;
                    let quantity = parseInt(rowData[2]);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="products[${index}][id]" value="${productId}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="products[${index}][quantity]" value="${quantity}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="products[${index}][price]" value="${price}">`);

                    // Calcula el subtotal para el total exacto
                    let subtotal = parseFloat(rowData[3].replace('$', '')) || 0;
                    totalExacto += subtotal;
                });
            }

            // Se lleva a cabo la lógica de redondeo
            let tipoDeRecibo = document.getElementById('receiptType').value;
            let totalAPagar = totalExacto;

            if (tipoDeRecibo === 'Efectivo') {
                totalAPagar = Math.round(totalExacto * 2) / 2;
            }

            let cantidadRecibida = parseFloat(cantidadRecibidaEl.value) || 0;
            let cambio = cantidadRecibida - totalAPagar;

            // Asigna los valores redondeados a los inputs que se envían al backend
            if (amountTotalInputEl) amountTotalInputEl.value = totalAPagar.toFixed(2);
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
