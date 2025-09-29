@extends('adminlte::page')

@section('title', 'Crear Categoría')

@section('content_header')
    <h1><b>Crear Nueva Categoría</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form id="formCategories" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre de la Categoría</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ej. Analgésicos" required>
                    </div>
                    <div class="col-md-6">
                        <label for="type" class="form-label">Tipo</label>
                        {{-- CAMBIO: Input de texto a Select --}}
                        <select name="type" id="type" class="form-control" required>
                            <option value="" selected disabled>Seleccione un tipo</option>
                            <option value="Analgésico">Analgésico</option>
                            <option value="Antibiótico">Antibiótico</option>
                            <option value="Antiinflamatorio">Antiinflamatorio</option>
                            <option value="Antihistamínico">Antihistamínico</option>
                            <option value="Antipirético">Antipirético</option>
                            <option value="Jarabe">Jarabe</option>
                            <option value="Ungüento">Ungüento</option>
                            <option value="Suplemento">Suplemento</option>
                            <option value="Vitamina">Vitamina</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Descripción (Opcional)</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Pequeña descripción de la categoría" rows="3"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-primary" id="btnSave">
                        <i class="fas fa-save"></i> Guardar Categoría
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
            const form = document.getElementById('formCategories');
            const btnSave = document.getElementById('btnSave');

            btnSave.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "¿Crear la categoría?",
                    text: "Se creará una nueva categoría en el sistema.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "Sí, crear",
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
                title: 'Error al crear la categoría',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop