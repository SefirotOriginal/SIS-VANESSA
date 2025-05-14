@extends('adminlte::page')

@section('title', 'Nueva Venta')

@section('content_header')
<h1><b>Registro de venta</b></h1>
@stop

@section('content')
{{-- Div contenedor de campos: código, producto, precio, stock y cantidad --}}
<div class="card shadow">
    <div class="card-body">
        <form id="formVenta" action="{{ route('ventas.crear') }}" method="POST">
        @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="producto" class="form-label">Producto</label>
                    <select class="form-select" id="productoSelect">
                        <option value="">-- Seleccionar producto --</option>
                        @foreach ($productos as $producto)
                            <option 
                                value="{{ $producto->id }}" 
                                data-codigo="{{ $producto->barCode }}"
                                data-nombre="{{ $producto->name }}"
                                data-precio="{{ $producto->salePrice }}"
                                data-stock="{{ $producto->currentStock }}">
                                {{ $producto->name }}
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
                    <input type="number" class="form-control" id="precio" placeholder="$0.00" readonly>
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

            {{-- Sección dividida en 2 columnas horizontales --}}
            <div class="row mt-4">
                <!-- Tabla de detalles de venta -->
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
                            <tr style="display:none;">
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Panel de totales y confirmación -->
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="receiptType" class="form-label">Tipo de recibo:</label>
                                <select name="receiptType" id="receiptType">
                                    <option value="Efectivo" selected>Efectivo</option>
                                    <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <h5>Total a pagar: <strong id="totalPagar" class="text-success">$0.00</strong></h5>
                            <input type="hidden" name="amountPayment" id="amountPayment" value="11">

                            <div class="form-group mt-3">
                                <label for="cantidadRecibida">Cantidad recibida</label>
                                <input type="number" class="form-control" name="amountTotal" id="cantidadRecibida" placeholder="Monto entregado" value="0">
                            </div>

                            <h5 class="mt-3">Cambio: <strong id="cambio" class="text-warning">$0.00</strong></h5>
                            <input type="hidden" name="amountExchange" id="amountExchange" value="63">

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
    console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>

<script>
    new DataTable('#ventaCrear', {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        paging: false,          // Oculta la paginación
        searching: false,       // Oculta la barra de búsqueda
        info: false             // Oculta el texto "Mostrando registros del 1 al 5 de un total de 30"
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formVenta');
        const btnGuardar = document.getElementById('btnGuardar');

        btnGuardar.addEventListener('click', function () {
            // Validaciones personalizadas
            let total = parseFloat(document.getElementById('totalPagar').innerText.replace('$', '')) || 0;
            let cantidadRecibida = parseFloat(document.getElementById('cantidadRecibida').value) || 0;
            let filas = document.querySelectorAll('#detalleVenta tr');

            // Validación: El total a pagar no puede ser 0
            if (total === 0) {
                Swal.fire('El total a pagar no puede ser $0.00', '', 'warning');
                return;
            }

            // Validación: Debe haber al menos un producto agregado
            if (filas.length < 2) { // La primera fila es la vacía oculta
                Swal.fire('Debe agregar al menos un producto a la venta', '', 'warning');
                return;
            }

            // Validación: La cantidad recibida no puede ser menor que el total a pagar
            if (cantidadRecibida < total) {
                Swal.fire('La cantidad recibida no puede ser menor que el total a pagar', '', 'warning');
                return;
            }

            // Validación HTML5 (por si tienes campos requeridos en el formulario)
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Confirmación final
            Swal.fire({
                title: "¿Registrar la venta?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Registrar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
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

<script>
    $('#btnAgregar').click(function() {
        let productoId = $('#productoSelect').val();
        let nombre = $('#productoSelect option:selected').data('nombre');
        let precio = parseFloat($('#precio').val());
        let cantidad = parseInt($('#cantidad').val());
        let subtotal = precio * cantidad;

        if (!productoId || cantidad <= 0) {
            Swal.fire('Selecciona un producto y cantidad válida');
            return;
        }

        $('#detalleVenta').append(`
            <tr>
                <td data-id="${productoId}">${nombre}</td>
                <td>$${precio.toFixed(2)}</td>
                <td>${cantidad}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button onclick="$(this).closest('tr').remove(); actualizarTotal();" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
        actualizarTotal();
        limpiarCampos();
    });
</script>

<script>
    function actualizarTotal() {
        let total = 0;
        $('#detalleVenta tr').each(function() {
            let subtotal = parseFloat($(this).find('td:eq(3)').text().replace('$', ''));
            total += subtotal;
        });
        $('#totalPagar').text(`$${total.toFixed(2)}`);

        let recibido = parseFloat($('#cantidadRecibida').val());
        let cambio = recibido - total;
        $('#cambio').text(`$${cambio.toFixed(2)}`);
    }
    $('#cantidadRecibida').on('input', actualizarTotal);
    
    function limpiarCampos() {
        $('#productoSelect').val('');
        $('#precio').val('');
        $('#stock').val('');
        $('#cantidad').val('1');
    }
</script>

<script>
    $('#productoSelect').on('change', function() {
        let selected = $(this).find(':selected');
        let precio = selected.data('precio');
        let stock = selected.data('stock');

        $('#precio').val(precio);
        $('#stock').val(stock);
    });
</script>

<script>
    document.getElementById('productoSelect').addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        document.getElementById('codigo').value = option.dataset.codigo || '';
        document.getElementById('precio').value = option.dataset.precio || '';
        document.getElementById('stock').value = option.dataset.stock || '';
    });
</script>
@stop
