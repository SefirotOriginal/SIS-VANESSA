@extends('adminlte::page')

@section('title', 'Crear Rol')

@section('content_header')
    <h1><b>Registro de roles</b></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card shadow w-100">
            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label"><b>Nombre del rol</b></label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Ej. Administrador" required>
                        <input type="hidden" name="guard_name" value="web">
                    </div>
                    <div class="mb-3">
                        <label for="permisos" class="form-label"><b>Permisos</b></label>
                        <div class="form-check form-switch">
                            @foreach ($permissions as $permission)
                                {{-- Lista de permisos (Esto ya toma los datos desde la db gonzalo) --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $permission->name }}" id="perm_{{ $permission->name }}">
                                    <label class="form-check-label"
                                        for="perm_{{ $permission->name }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        {{-- <button onclick="alertaConfirmar()" type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Rol
                        </button> --}}
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
        html,
        body {
            height: 100%;
            /* overflow: hidden; */
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    {{-- <script>
        function alertaConfirmar() {
            Swal.fire({
                title: "¿Registrar el nuevo rol?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Registrar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    Swal.fire("¡Rol registrado!", "", "success");
                    // Redirige a la ruta deseada
                    window.location.href = 'roles.index';
                }
            });
        }
    </script> --}}
@stop
