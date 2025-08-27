
@extends('adminlte::page')

@section('title', 'Crear Laboratorio')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow" style="width: 430px; border-radius: 20px;">
            <div class="card-header text-center"
                style="background: linear-gradient(90deg, #3b82f6 0%, #6d28d9 100%); border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png" alt="Etiqueta" width="40" class="mb-2">
                <h2 class="mb-0 text-white" style="font-weight: bold;">Crear Laboratorio</h2>
            </div>
            <div class="card-body"
                style="background: #fff; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <form action="{{ route('laboratories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label" style="font-weight: 500;">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Ingrese el nombre del laboratorio" required>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label" style="font-weight: 500;">Estado</label>
                        <input type="text" name="state" id="state" class="form-control"
                            placeholder="Especifique el estado de procedencia del laboratorio" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label" style="font-weight: 500;">Contacto</label>
                        <input type="text" name="contact" id="contact" class="form-control"
                            placeholder="Escriba el contacto del laboratorio" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100" style="font-weight: bold;">
                        <i class="fas fa-save"></i> Guardar laboratorio
                    </button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header img {
            margin-bottom: 8px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px #3b82f6;
        }
    </style>
@stop

@section('js')
    <script>
        // Contador de caracteres para descripción
        document.getElementById('description').addEventListener('input', function() {
            document.getElementById('descCount').textContent = this.value.length + '/200';
        });
    </script>
@stop
