@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
<h1><b>Registro de producto</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Paracetamol">
                </div>
                <div class="col-md-4">
                    <label for="concentracion" class="form-label">Concentración</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="concentracion_cantidad" name="concentracion_cantidad" placeholder="Cantidad">
                        <select class="form-select" id="concentracion_unidad" name="concentracion_unidad">
                            <option value="mg">mg</option>
                            <option value="gr">gr</option>
                            <option value="ml">ml</option>
                            <option value="lts">lts</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="presentacion" class="form-label">Presentación</label>
                    <div class="input-group">
                        <select class="form-select" id="presentacion" name="presentacion">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="Caja con 10 tabletas">Caja con 10 tabletas</option>
                            <option value="Caja con 20 tabletas">Caja con 20 tabletas</option>
                            <option value="Caja con 30 tabletas">Caja con 30 tabletas</option>
                            <option value="Frasco con solución">Frasco con solución</option>
                            <option value="Ampolla">Ampolla</option>
                            <option value="Sobres">Sobres</option>
                            <option value="Pomada">Pomada</option>
                            <option value="Spray">Spray</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="usos" class="form-label">Usos comunes</label>
                    <input type="text" class="form-control" id="usos" name="usos" placeholder="Ej. Fiebre, dolor">
                </div>
                <div class="col-md-4">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" class="form-control" id="marca" name="marca" placeholder="Ej. Genéricos S.A.">
                </div>
                <div class="col-md-4">
                    <label for="categoria" class="form-label">Categoría</label>
                    <div class="input-group">
                        <select class="form-select" id="concentracion_unidad" name="concentracion_unidad">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <option value="mg">Analgésico</option>
                            <option value="gr">Antibiótico</option>
                            <option value="ml">Antiinflamatorio</option>
                            <option value="lts">Antihistamínico</option>
                            <option value="lts">Antigripal</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="proveedor" class="form-label">Proveedor</label>
                    <input type="text" class="form-control" id="proveedor" name="proveedor" placeholder="Ej. FarmaDistribuidora">
                </div>
                <div class="col-md-4">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" min="0">
                </div>
                <div class="col-md-4">
                    <label for="lote" class="form-label">Lote</label>
                    <input type="text" class="form-control" id="lote" name="lote" placeholder="Ej. L12345">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="caducidad" class="form-label">Fecha de caducidad</label>
                    <input type="date" class="form-control" id="caducidad" name="caducidad">
                </div>
                <div class="col-md-4">
                    <label for="precio" class="form-label">Precio al público</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0">
                </div>
            </div>

            <div class="mt-4">
                <button onclick="alertaConfirmar()" type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar producto
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </div>
</div>
@stop

@section('css')
<style>
    html, body {
        height: 100%;
        overflow-x: hidden;
    }
    .content-wrapper {
        background-color: #f1f1f1;
    }
    .card-body {
        background-color: #ffffff;
    }
    .form-label {
        font-weight: bold;
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
        function alertaConfirmar() {
            Swal.fire({
            title: "¿Registrar el producto?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Registrar",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("¡Producto registrado!", "", "success");
                // Redirige a la ruta deseada
                window.location.href = 'consultas';
            }
            });
        }
    </script>
@stop

