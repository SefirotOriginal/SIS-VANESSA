@extends('adminlte::page')

@section('title', 'Crear Laboratorio')

@section('content_header')
    <h1><b>Crear Nuevo Laboratorio</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            @can('laboratory.store')

                <form id="formLabs" action="{{ route('laboratories.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre del Laboratorio</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Ej. Bayer"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">Estado</label>
                            <select name="state" id="state" class="form-control" required>
                                <option value="" selected disabled>Seleccione un estado</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="contact" class="form-label">Contacto (Teléfono o Email)</label>
                            <input type="text" name="contact" id="contact" class="form-control"
                                placeholder="Ej. 55-1234-5678 o contacto@bayer.com" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('laboratories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-primary" id="btnSave">
                            <i class="fas fa-save"></i> Guardar Laboratorio
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
            const btnSave = document.getElementById('btnSave');

            btnSave.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "¿Crear el laboratorio?",
                    text: "Se registrará un nuevo laboratorio en el sistema.",
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
    {{-- Script para mostrar errores de validación del backend --}}
    <script>
        @if ($errors->any())
            Swal.fire({
                title: 'Error al crear el laboratorio',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
