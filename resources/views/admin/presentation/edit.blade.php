@extends('adminlte::page')

@section('title', 'Editar Presentación')

@section('content_header')
    <h1><b>Editar Presentación:</b> {{ $presentation->name }}</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form id="formPresentation" action="{{ route('presentations.update', $presentation) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3 justify-content-center">
                    <div class="col-md-12">
                        <label for="name" class="form-label">Nombre de la Presentación</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $presentation->name) }}" required>
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea type="text" name="description" id="description" class="form-control" required>{{ old('description', $presentation->description) }}</textarea>
                    </div>
                </div>
                {{-- Campo contact eliminado por el usuario: no pertenece al formulario de presentaciones --}}

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('presentations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-success" id="btnUpdate">
                        <i class="fas fa-sync-alt"></i> Guardar Cambios
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
            const form = document.getElementById('formPresentation');
            const btnUpdate = document.getElementById('btnUpdate');

            btnUpdate.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "¿Guardar los cambios?",
                    text: "Se actualizará la información de la presentación.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "Sí, guardar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    {{-- Script para mostrar errores de validación del backend --}}
    <script>
        @if ($errors->any())
            Swal.fire({
                title: 'Error al actualizar la presentación',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
