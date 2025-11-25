@extends('adminlte::page')

@section('title', 'Editar Laboratorio')

@section('content_header')
    <h1><b>Editar Laboratorio:</b> {{ $laboratory->name }}</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            @can('laboratory.update')

                <form id="formLabs" action="{{ route('laboratories.update', $laboratory) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre del Laboratorio</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $laboratory->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">Estado</label>
                            <select name="state" id="state" class="form-control" required>
                                <option value="" disabled>Seleccione un estado</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}"
                                        {{ old('state', $laboratory->state) == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="contact" class="form-label">Contacto (Teléfono o Email)</label>
                            <input type="text" name="contact" id="contact" class="form-control"
                                value="{{ old('contact', $laboratory->contact) }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('laboratories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-success" id="btnUpdate">
                            <i class="fas fa-sync-alt"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            @endcan
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formLabs');
            const btnUpdate = document.getElementById('btnUpdate');

            btnUpdate.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "¿Guardar los cambios?",
                    text: "Se actualizará la información del laboratorio.",
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
                title: 'Error al actualizar el laboratorio',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
