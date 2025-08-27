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
                                <label for="batch_number" class="form-label">Número de lote</label>
                                <input type="text" name="batch_number" id="batch_number" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="product_presentation_id" class="form-label">Producto</label>
                                <select name="product_presentation_id" id="product_presentation_id" class="form-control form-select" required>
                                    <option value="" selected disabled>Selecciona un producto</option>
                                    @foreach ($products_presentation as $presentation)
                                        <option value="{{ $presentation->id }}">{{ $presentation->product->name }} ({{ $presentation->presentation->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="creation_date" class="form-label">Fecha de fabricación</label>
                                <input type="date" name="creation_date" id="creation_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="expiration_date" class="form-label">Fecha de expiración</label>
                                <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" name="stock" id="stock" class="form-control" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label for="minStock" class="form-label">Stock mínimo</label>
                                <input type="number" name="min_stock" id="min_stock" class="form-control" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label for="maxStock" class="form-label">Stock máximo</label>
                                <input type="number" name="max_stock" id="max_stock" class="form-control" required min="0">
                            </div>
                        </div>
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
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formBatches');
            const btnSave = document.getElementById('btnSave');

            btnSave.addEventListener('click', function() {
                // Forzamos validación HTML5
                if (!form.checkValidity()) {
                    // Dispara validación nativa del navegador
                    form.reportValidity();
                    return;
                }

                // Si todo está bien, muestra el SweetAlert
                Swal.fire({
                    title: "¿Registrar el lote?",
                    text: "Se creará un nuevo lote en el sistema.",
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
                title: 'Error al registrar el lote',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
