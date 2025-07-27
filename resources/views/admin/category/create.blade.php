@extends('adminlte::page')

@section('title', 'Crear Categoría')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow" style="width: 430px; border-radius: 20px;">
            <div class="card-header text-center"
                style="background: linear-gradient(90deg, #3b82f6 0%, #6d28d9 100%); border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png" alt="Etiqueta" width="40" class="mb-2">
                <h2 class="mb-0 text-white" style="font-weight: bold;">Crear Categoría</h2>
            </div>
            <div class="card-body"
                style="background: #fff; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label" style="font-weight: 500;">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Ingrese el nombre de la categoría" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label" style="font-weight: 500;">Tipo</label>
                        <input type="text" name="type" id="type" class="form-control"
                            placeholder="Especifique el tipo de categoría" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label" style="font-weight: 500;">Descripción</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Describa la categoría (opcional)"
                            maxlength="200"></textarea>
                        <small class="text-muted float-end" id="descCount">0/200</small>
                    </div>
                    <button type="submit" class="btn btn-success w-100" style="font-weight: bold;">
                        <i class="fas fa-save"></i> Guardar Categoría
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
