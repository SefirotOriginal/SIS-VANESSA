@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
<h1><b>Edición de producto</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form id="formProducto" action="{{ route('productos.edicion', $producto->id) }}" method="POST">
        @csrf
        @method('PUT')
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Código de barras</label>
                    <input type="number" name="barCode" class="form-control" value="{{ $producto->barCode }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ $producto->name }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="description"  class="form-control" value="{{ $producto->description }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Stock actual</label>
                    <input type="number" name="currentStock" class="form-control" value="{{ $producto->currentStock }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stock mínimo</label>
                    <input type="number" name="mintStock" class="form-control" value="{{ $producto->mintStock }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stock máximo</label>
                    <input type="number" name="maxStock" class="form-control" value="{{ $producto->maxStock }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Precio de compra</label>
                    <input type="number" name="purchasePrice" class="form-control" step="0.01" value="{{ $producto->purchasePrice }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Precio de venta</label>
                    <input type="number" name="salePrice" class="form-control" step="0.01" value="{{ $producto->salePrice }}" required>
                </div>
                <div class="col-md-4">
                    <label for="category_id" class="form-label d-block">Categoría</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ $producto->category_id == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="laboratory_id" class="form-label d-block">Laboratorio</label>
                    <select name="laboratory_id" id="laboratory_id" class="form-select" required>
                        @foreach($laboratorios as $lab)
                            <option value="{{ $lab->id }}" {{ $producto->laboratory_id == $lab->id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="batch_id" class="form-label d-block">Lote</label>
                    <select name="batch_id" id="batch_id" class="form-select" required>
                        @foreach($lotes as $lote)
                            <option value="{{ $lote->id }}" {{ $producto->batch_id == $lote->id ? 'selected' : '' }}>
                                Lote #{{ $lote->batchNumber }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="button" class="btn btn-success" id="btnGuardar">
                    <i class="fas fa-save"></i> Guardar producto
                </button>
                <a href="{{ route('productos.consulta') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
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
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formProducto');
        const btnGuardar = document.getElementById('btnGuardar');

        btnGuardar.addEventListener('click', function () {
            // Forzamos validación HTML5
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Confirmación con SweetAlert
            Swal.fire({
                title: "¿Guardar los cambios realizados?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Envía el formulario
                }
            });
        });
    });
</script>
@stop

