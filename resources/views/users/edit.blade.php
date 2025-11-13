@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1><b>Edición de usuario</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form id="formUsuario" action="{{ route('users.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
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
                    <label for="password" class="form-label">Contraseña (Opcional)</label>
                    <input type="password" name="password" class="form-control" value="" placeholder="Dejar en blanco para no cambiar">
                </div>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>

                <select id="rol" name="role" class="form-control form-select">
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

                <select class="form-control form-select" id="estado">
                    <option disabled>Selecciona el estado</option>
                    <option selected value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="imagenPerfil" class="form-label">Imagen de perfil</label>
                <input class="form-control" type="file" name="imagenPerfil" id="imagenPerfil" accept="image/*">
            </div>
            <div class="mb-3 text-center">
                @php
                    // Determina la imagen a mostrar
                    $imageSrc = $usuario->profile_photo_path 
                                ? asset('storage/' . $usuario->profile_photo_path) 
                                : '#';
                    // Determina si se debe mostrar
                    $displayStyle = $usuario->profile_photo_path 
                                ? 'display: block;' 
                                : 'display: none;';
                @endphp
                <img id="previewImagen" src="{{ $imageSrc }}" alt="Vista previa" 
                    class="img-thumbnail rounded-circle" 
                    style="max-width: 150px; {{ $displayStyle }}">
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <div>
                    <button type="button" class="btn btn-success" id="btnGuardar">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </div>
        </form>
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Script de preview de imagen
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

    // Script de confirmación de guardado
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
                title: "¿Actualizar el usuario?",
                text: "Los cambios se guardarán en el sistema.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color verde
                cancelButtonColor: '#6c757d', // Color gris
                confirmButtonText: "Sí, actualizar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Envía el formulario
                }
            });
        });
    });
</script>
@stop