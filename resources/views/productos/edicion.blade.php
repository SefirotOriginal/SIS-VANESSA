@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
<h1><b>Edición de producto</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="Paracetamol">
                </div>

                <div class="col-md-4">
                    <label for="concentracion" class="form-label">Concentración</label>
                    <div class="input-group">
                        <input type="number" name="concentracion_cantidad" class="form-control" value="500">
                        <select name="concentracion_unidad" class="form-select">
                        <option value="mg" selected>mg</option>
                        <option value="gr">gr</option>
                        <option value="ml">ml</option>
                        <option value="lts">lts</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="presentacion" class="form-label">Presentación</label>
                    <div class="input-group">
                        <select name="presentacion" class="form-select">
                            <option disabled>Seleccione una opción</option>
                            <option value="Caja con 10 tabletas">Caja con 10 tabletas</option>
                            <option value="Caja con 20 tabletas" selected>Caja con 20 tabletas</option>
                            <option value="Caja con 30 tabletas">Caja con 30 tabletas</option>
                            <option value="Frasco 60 ml">Frasco 60 ml</option>
                            <option value="Frasco 120 ml">Frasco 120 ml</option>
                            <option value="Frasco 240 ml">Frasco 240 ml</option>
                            <option value="Inyectable">Inyectable</option>
                            <option value="Pomada">Pomada</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="usos" class="form-label">Usos comunes</label>
                    <input type="text" name="usos" class="form-control" value="Alivio de dolor, fiebre">
                </div>

                <div class="col-md-4">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" name="marca" class="form-control" value="Genéricos S.A.">
                </div>

                <div class="col-md-4">
                    <label for="categoria" class="form-label">Categoría</label>
                    <div class="input-group">
                        <select name="categoria" class="form-select">
                            <option disabled>Seleccione una categoría</option>
                            <option value="Analgésico" selected>Analgésico</option>
                            <option value="Antibiótico">Antibiótico</option>
                            <option value="Antiinflamatorio">Antiinflamatorio</option>
                            <option value="Antihistamínico">Antihistamínico</option>
                            <option value="Antigripal">Antigripal</option>
                            <option value="Antipirético">Antipirético</option>
                            <option value="Antiséptico">Antiséptico</option>
                            <option value="Anticonvulsivo">Anticonvulsivo</option>
                            <option value="Ansiolítico">Ansiolítico</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="proveedor" class="form-label">Proveedor</label>
                    <input type="text" class="form-control" id="proveedor" name="proveedor" value= "FarmaDistribuidora">
                </div>
                <div class="col-md-4">
                    <label for="precio" class="form-label">Precio al público</label>
                    <input type="number" class="form-control" id="precio" name="precio" value= "45" step="0.01" min="0">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('productos.consulta') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button onclick="alertaConfirmar()" type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
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
            title: "¿Guardar los cambios realizados?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("¡Los cambios han sido guardados!", "", "success");
                // Redirige a la ruta deseada
                window.location.href = 'consultas';
            }
            });
        }
    </script>
@stop

