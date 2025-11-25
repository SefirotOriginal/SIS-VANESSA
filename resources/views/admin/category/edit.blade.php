@extends('adminlte::page')

@section('title', 'Editar Categoría')

@section('content_header')
    <h1><b>Editar Categoría</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form id="formCategories" action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre de la Categoría</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $category->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="type" class="form-label">Tipo</label>
                        @php
                            $types = [
                                'Analgésico',
                                'Antibiótico',
                                'Antiinflamatorio',
                                'Antihistamínico',
                                'Antipirético',
                                'Jarabe',
                                'Ungüento',
                                'Suplemento',
                                'Vitamina',
                                'Otro',
                            ];
                        @endphp
                        <select name="type" id="type" class="form-control" required>
                            <option value="" disabled>Seleccione un tipo</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}" @if ($category->type == $type) selected @endif>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Descripción (Opcional)</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    @can('category.index')
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    @endcan

                    @can('category.edit')
                        {{-- CAMBIO: Se cambió btn-primary por btn-success para el color verde --}}
                        <button type="button" class="btn btn-success" id="btnSave">
                            <i class="fas fa-sync-alt"></i> Guardar Cambios
                        </button>
                    @endcan
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
                    title: "¿Actualizar la categoría?",
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
    <script>
        @if ($errors->any())
            Swal.fire({
                title: 'Error al actualizar la categoría',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
