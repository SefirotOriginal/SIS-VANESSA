@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1><b>Crear de producto</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form id="formProducto" action="{{ route('products.create') }}" method="POST">
                @csrf
                <div class="card shadow">
                    <section class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Código de barras</label>
                                <input type="number" name="barCode" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="">
                            {{-- <label for="exampleFormControlTextarea1" class="form-label">Descripción</label>
                            <textarea class="form-control" name="desciption" id="exampleFormControlTextarea1" rows="3" required> --}}
                            <label for="exampleFormControlTextarea1" class="form-label">Descripción</label>
                            <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3" required></textarea>
                        </div>
                    </section>
                </div>

                {{-- <div class="card shadow">
                    <section class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Stock actual</label>
                                <input type="number" name="currentStock" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stock mínimo</label>
                                <input type="number" name="mintStock" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stock máximo</label>
                                <input type="number" name="maxStock" class="form-control" required>
                            </div>
                        </div>
                        <div class="col">
                            <label for="batch_id" class="form-label d-block">Lote</label>
                            <select name="batch_id" id="batch_id" class="select form-control" required>
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}">Lote #{{ $batch->batchNumber }}</option>
                                @endforeach
                            </select>
                        </div>
                    </section>
                </div> --}}

                <div class="card shadow">
                    <section class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Precio de compra</label>
                                <input type="number" name="purchasePrice" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Precio de venta</label>
                                <input type="number" name="salePrice" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label for="category_id" class="form-label d-block">Categoría</label>
                                <select name="category_id" id="category_id" class="select form-control" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($categories as $categories)
                                        <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="laboratory_id" class="form-label d-block">Laboratorio</label>
                                <select name="laboratory_id" id="laboratory_id" class="select form-control" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($laboratories as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-success" id="btnGuardar">
                        <i class="fas fa-save"></i> Guardar producto
                    </button>
                    <a href="consultas" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        html,
        body {
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formProducto');
            const btnGuardar = document.getElementById('btnGuardar');

            btnGuardar.addEventListener('click', function() {
                // Forzamos validación HTML5
                if (!form.checkValidity()) {
                    // Dispara validación nativa del navegador
                    form.reportValidity();
                    return;
                }

                // Si todo está bien, muestra el SweetAlert
                Swal.fire({
                    title: "¿Registrar el producto?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Registrar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Envía el formulario
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
@stop
