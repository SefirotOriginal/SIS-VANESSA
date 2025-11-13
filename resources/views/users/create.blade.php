@extends('adminlte::page')

@section('title', 'Crear Usuario')

{{-- Use the standard header --}}
@section('content_header')
    <h1><b>Registrar Nuevo Usuario</b></h1>
@stop

@section('content')
    {{-- Use the standard card structure --}}
    <div class="card shadow">
        <div class="card-body">
            <form id="formUsuario" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data"> {{-- Added enctype for file upload --}}
                @csrf

                {{-- Row for Name and Email --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>

                {{-- Row for Phone and Password --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="phoneNumber" class="form-label">Número telefónico</label>
                        <input type="text" name="phoneNumber" id="phoneNumber" class="form-control"> {{-- Removed required if nullable --}}
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                </div>

                {{-- Row for Role --}}
                <div class="row mb-3">
                    <div class="col-md-12"> {{-- Takes full width --}}
                        <label for="role" class="form-label">Rol</label>
                        <select id="role" name="role" class="form-control form-select" required>
                            <option value="" selected disabled>Selecciona un rol</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row for Profile Image (Unique to users) --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="imagenPerfil" class="form-label">Imagen de perfil (Opcional)</label>
                        <input class="form-control" type="file" name="imagenPerfil" id="imagenPerfil" accept="image/*"> {{-- Added name attribute --}}
                    </div>
                </div>
                <div class="mb-3 text-center">
                    <img id="previewImagen" src="#" alt="Vista previa" class="img-thumbnail rounded-circle"
                        style="max-width: 150px; display: none;">
                </div>

                {{-- Standard button layout --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary"> {{-- Changed route to users.index --}}
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-primary" id="btnGuardar"> {{-- Changed color to blue --}}
                        <i class="fas fa-save"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Keep consistent basic styles --}}
    <style>
        .content-wrapper { background-color: #f1f1f1; }
        .card-body { background-color: #ffffff; }
    </style>
@stop

@section('js')
    {{-- Use consistent JS including SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Image preview script (kept from original)
        document.getElementById('imagenPerfil').addEventListener('change', function(e) {
            const preview = document.getElementById('previewImagen');
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = () => {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                 preview.style.display = 'none'; // Hide if no valid image
                 preview.src = '#';
            }
        });

        // SweetAlert confirmation script (adapted from batches)
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formUsuario');
            const btnGuardar = document.getElementById('btnGuardar');

            btnGuardar.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                Swal.fire({
                    title: "¿Registrar el usuario?", // Adapted text
                    text: "Se creará un nuevo usuario en el sistema.", // Adapted text
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#007bff', // Blue confirmation button
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "Sí, registrar", // Adapted text
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Script to show validation errors (adapted from batches)
        @if ($errors->any())
            Swal.fire({
                title: 'Error al registrar', // Adapted text
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop