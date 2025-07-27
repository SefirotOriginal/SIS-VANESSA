@extends('adminlte::page')

@section('title', 'Crear Lote')

@section('content_header')
    <h1><b>Registro de lotes</b></h1>
@stop

@section('content')
    <div class="container-fluid px-0">
        <div class="card shadow">
            <div class="card-body">
                <form id="formBatches" action="{{ route('batches.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="batchNumber" class="form-label">Numero de lote</label>
                                <input type="number" name="batchNumber" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="product" class="form-label">Producto</label>
                                <select name="product" class="form-control form-select" required>
                                    <option value="" selected disabled>Selecciona un producto</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="creationDate" class="form-label">Fecha de fabricación</label>
                                <input type="date" name="creationDate" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="expirationDate" class="form-label">Fecha de expiración</label>
                                <input type="date" name="expirationDate" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="minStock" class="form-label">Stock mainimo</label>
                                <input type="number" name="stock" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="maxStock" class="form-label">Stock maximo</label>
                                <input type="number" name="stock" class="form-control" required>
                            </div>
                        </div>


                        {{-- <label for="rol" class="form-label">Rol</label>
                        <select id="rol" name="role" class="form-control form-select" required>
                            <option value="" selected disabled>Selecciona un rol</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select> --}}
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-success" id="btnSave">
                            <i class="fas fa-save"></i> Guardar lote
                        </button>
                        <a href="{{route('batches.index')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .content-wrapper {
            background-color: #f1f1f1;
        }

        .card-body {
            background-color: #ffffff;
        }
    </style>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        .content-wrapper {
            background-color: #f1f1f1;
        }

        .card-body {
            background-color: #ffffff;
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
        document.getElementById('imagenPerfil').addEventListener('change', function(e) {
            const preview = document.getElementById('previewImagen');
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formUsuario');
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
                    title: "¿Registrar el usuario?",
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
