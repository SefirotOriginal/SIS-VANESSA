@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1><b>Edición de usuario</b></h1>
@stop

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow">
        <div class="card-body">
            <form id="formUsuario" action="{{ route('users.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="usuario" class="form-label">Número telefónico</label>
                        <input type="number" name="phoneNumber" class="form-control" id="telefono" value="{{ $usuario->phoneNumber }}">
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" value="">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select id="rol" name="role" class="form-select">
                        <option disabled>Selecciona un rol</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->name }}" {{ $usuario->roles->contains($rol->id) ? 'selected' : '' }}>
                                {{ $rol->name }}
                            </option>
                        @endforeach
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
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <div>
                        @if ($usuario->id !== auth()->id())
                        <button type="button" class="btn btn-danger me-2" title="Eliminar" onclick="confirmarEliminacion({{ $usuario->id }})">
                            <i class="fas fa-trash-alt"></i> Eliminar usuario
                        </button>
                        @endif
                        <button type="button" class="btn btn-success" id="btnGuardar">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>
                </div>
            </form>
            {{-- <form id="formEliminar{{ $usuario->id }}" action="{{ route('usuarios.eliminar', $usuario->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            </form> --}}
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
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formUsuario');
        const btnGuardar = document.getElementById('btnGuardar');

        btnGuardar.addEventListener('click', function () {
            // Forzamos validación HTML5
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Confirmación con SweetAlert
            Swal.fire({
                title: "¿Guardar los cambios realizados?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Envía el formulario
                }
            });
        });
    });
</script>
<script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡Esta acción no se puede deshacer!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEliminar' + id).submit();
            }
        });
    }
</script>
@stop
