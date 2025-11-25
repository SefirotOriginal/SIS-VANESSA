@extends('adminlte::page')

@section('title', 'Crear Precentacion')

@section('content_header')
    <h1><b>Crear Nueva Presentacion</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form id="formPresentation" action="{{ route('presentations.store') }}" method="POST">
                @csrf
                <div class=" row mb-3 justify-content-center">
                    <div class="col-md-12 ">
                        <label for="name" class="form-label">Nombre de presentación</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="col-md-12 ">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                        {{-- <input type="text" name="description" id="description" class="form-control" required> --}}
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    @can('presentation.index')
                        <a href="{{ route('presentations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    @endcan

                    @can('presentation.create')
                        <button type="button" class="btn btn-primary" id="btnSave">
                            <i class="fas fa-save"></i> Guardar Presentacion
                        </button>
                    @endcan
                </div>
            </form>
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
            const form = document.getElementById('formPresentation');
            const btnSave = document.getElementById('btnSave');

            btnSave.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Si todo está bien, muestra el SweetAlert
                Swal.fire({
                    title: "Crear el precentación?",
                    text: "Se creará un nuevo precentación en el sistema.",
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
                title: 'Error al registrar el presentación',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
