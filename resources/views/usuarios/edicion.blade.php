@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1><b>Edición de usuario</b></h1>
@stop

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow">
        <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" value="Ana Gómez">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" value="ana.gomez@email.com">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="usuario" class="form-label">Número telefónico</label>
                        <input type="number" class="form-control" id="telefono" value="3221084900">
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" value="3221084900">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select id="rol" class="form-select">
                        <option disabled>Selecciona un rol</option>
                        <option selected>Administrador</option>
                        <option>Encargado de inventario</option>
                        <option>Vendedor</option>
                        <option>Supervisor</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado">
                        <option disabled>Selecciona el estado</option>
                        <option selected value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="imagenPerfil" class="form-label">Imagen de perfil</label>
                    <input class="form-control" type="file" id="imagenPerfil" accept="image/*">
                </div>
                <div class="mb-3 text-center">
                    <img id="previewImagen" src="#" alt="Vista previa" class="img-thumbnail rounded-circle" style="max-width: 150px; display: none;">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <div>
                        <button onclick="alertaBorrar()" type="button" class="btn btn-danger me-2">
                            <i class="fas fa-trash-alt"></i> Eliminar usuario
                        </button>
                        <button onclick="alertaConfirmar()" type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>
                </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .content-wrapper {
        background-color: #f1f1f1;
    }
    .card-body {
        background-color: #ffffff;
    }
</style>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .content-wrapper{
            background-color:#f1f1f1;
        }
        .card-body{
            background-color:#ffffff;
        }
        .custom-header {
            background-color: #0077B6;
            color: white;
        }
    </style>
<style>
    .custom-header th {
        white-space: nowrap; /* Evita que el texto se divida */
    }
</style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script>
        document.getElementById('imagenPerfil').addEventListener('change', function (e) {
            const preview = document.getElementById('previewImagen');
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script>
        function alertaConfirmar() {
            Swal.fire({
            title: "¿Guardar los cambios?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("¡Los cambios han sido guardados!", "", "success");
                // Redirige a la ruta deseada
                window.location.href = 'consultas';
            }
            });
        }
        function alertaBorrar() {
            Swal.fire({
            title: "¿Estás seguro?",
            text: "¡Esta acción no se podrá revertir!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, ¡Eliminar!",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: "¡Eliminado!",
                text: "El usuario se ha eliminado de la lista.",
                icon: "success"
                });
                window.location.href = 'consultas';
            }
            });
        }
    </script>
@stop