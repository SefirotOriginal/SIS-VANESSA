@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Bienvenido</h1>
@stop

@section('content')
{{-- Div contenedor de campos: código, producto, precio, stock y cantidad --}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda: Formulario de entrada -->
            <div class="col-md-10">
                <form>
                    <div class="row">
                        
                        <div class="form-group col-md-2">
                            <label for="codigo">Código</label>
                            <input type="text" class="form-control" id="codigo" placeholder="Código del Producto">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="producto">Producto</label>
                            <input type="text" class="form-control" id="producto" placeholder="Nombre del Producto">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="precio">Precio</label>
                            <input type="text" class="form-control" id="precio" placeholder="Precio">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="stock">Stock</label>
                            <input type="text" class="form-control" id="stock" placeholder="Stock">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="cantidad">Cantidad</label>
                            <input type="text" class="form-control" id="cantidad" placeholder="Cantidad">
                        </div>
                    </div>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            
            <!-- Columna derecha: Tabla y totales -->
            <div class="col-md-2">
                
                <button type="submit" class="btn btn-primary">Añadir</button>
                <div class="mt-4">
                    <h4>Total a pagar: <span id="totalPagar">0.00</span></h4>
                    <div class="form-group">
                        <label for="cantidadRecibida">Cantidad recibida</label>
                        <input type="text" class="form-control" id="cantidadRecibida" placeholder="Cantidad recibida">
                    </div>
                    <h4>Cambio a dar: <span id="cambio">0.00</span></h4>
                </div>

                <button type="button" class="btn btn-success mt-3">Confirmar Venta</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')

@stop

@section('js')
<script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop