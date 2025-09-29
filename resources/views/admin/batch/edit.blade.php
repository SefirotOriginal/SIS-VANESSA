@extends('adminlte::page')

@section('title', 'Editar Lote')

@section('content_header')
    <h1><b>Editar Lote</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            {{-- El formulario apunta a la ruta 'update' y usa el método PUT --}}
            <form id="formBatches" action="{{ route('batches.update', $batch) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="batch_number" class="form-label">Número de lote</label>
                        <input type="text" name="batch_number" id="batch_number" class="form-control" value="{{ $batch->batch_number }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="product_presentation_id" class="form-label">Producto</label>
                        <select name="product_presentation_id" id="product_presentation_id" class="form-control form-select" required>
                            <option value="" disabled>Selecciona un producto</option>
                            @foreach ($products_presentation as $presentation)
                                <option value="{{ $presentation->id }}" {{ $presentation->id == $batch->product_presentation_id ? 'selected' : '' }}>
                                    {{ $presentation->product->name }} ({{ $presentation->presentation->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="creation_date" class="form-label">Fecha de fabricación</label>
                        <input type="date" name="creation_date" id="creation_date" class="form-control" value="{{ $batch->creation_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="expiration_date" class="form-label">Fecha de expiración</label>
                        <input type="date" name="expiration_date" id="expiration_date" class="form-control" value="{{ $batch->expiration_date->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="{{ $batch->stock }}" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label for="min_stock" class="form-label">Stock mínimo</label>
                        <input type="number" name="min_stock" id="min_stock" class="form-control" value="{{ $batch->min_stock }}" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label for="max_stock" class="form-label">Stock máximo</label>
                        <input type="number" name="max_stock" id="max_stock" class="form-control" value="{{ $batch->max_stock }}" required min="0">
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                     <a href="{{route('batches.index')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-success" id="btnSave">
                        <i class="fas fa-sync-alt"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formBatches');
            const btnSave = document.getElementById('btnSave');

            btnSave.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "¿Actualizar el lote?",
                    text: "Los cambios se guardarán en el sistema.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "Sí, actualizar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop