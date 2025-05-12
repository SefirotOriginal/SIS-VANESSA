@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1><b>Registro de usuario</b></h1>
@stop

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="usuario" class="form-label">Número telefónico</label>
                        <input type="number" class="form-control" id="telefono">
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                </div>
                <label for="rol" class="form-label">Rol</label>
                <select id="rol" class="form-select">
                    <option selected disabled>Selecciona un rol</option>
                    <option>Administrador</option>
                    <option>Supervisor</option>
                    <option>Vendedor</option>
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
                <button onclick="alertaConfirmar()" type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Usuario
                </button>
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
            title: "¿Registrar el nuevo usuario?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Registrar",
            cancelButtonText: "Cancelar"
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("¡Usuario registrado!", "", "success");
                // Redirige a la ruta deseada
                window.location.href = 'consultas';
            }
            });
        }
    </script>
@stop