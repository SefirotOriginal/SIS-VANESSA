@extends('adminlte::page')

@section('title', 'Editar Rol')

@section('content_header')
    <h1><b>Editar rol</b></h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card shadow w-100">
            <!-- El formulario principal ahora envuelve todo el card-body -->
            <form id="editRoleForm" action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label"><b>Nombre del rol</b></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}"
                            required>
                        <input type="hidden" name="guard_name" value="{{ $role->guard_name ?? 'web' }}">
                    </div>
                    <div class="mb-3">
                        {{-- <label for="permisos" class="form-label"><b>Permisos</b></label>
                        @foreach ($permissions as $permission)
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $permission->name }}" id="perm_{{ $permission->name }}"
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="perm_{{ $permission->name }}">{{ $permission->name }}</label>
                            </div>
                        @endforeach --}}
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Permisos:</strong>
                                <br />
                                @foreach ($permission as $value)
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="perm_{{ $value->id }} "><input
                                                type="checkbox" name="permission[{{ $value->id }}]"
                                                value="{{ $value->id }}" class="form-check-input"
                                                {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                            {{ $value->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mb-3"><i
                                    class="fa-solid fa-floppy-disk"></i> Submit</button>
                        </div> --}}
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <div>
                            {{-- El botón de eliminar ahora es de tipo 'button' para prevenir submit del form principal --}}
                            <button type="button" onclick="alertaBorrar()" class="btn btn-danger me-2">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                            {{-- El botón de guardar ahora es de tipo 'button' para ser manejado por JS --}}
                            {{-- <button type="submit" onclick="alertaConfirmar()" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar cambios
                            </button> --}}
                            <button type="submit" onclick="alertaConfirmar()"" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Rol
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            {{-- Formulario separado para la eliminación, puede permanecer fuera del card-body si se prefiere, o dentro si la estructura lo requiere. --}}
            {{-- Lo mantendremos al mismo nivel que el form de edición para simplicidad, pero fuera del card-body del form de edición. --}}
            <form id="deleteRoleForm" action="{{ route('roles.destroy', $role->id) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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
                    window.location.href = '{{ route('roles.index') }}';
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
                        text: "El rol se ha eliminado de la lista.",
                        icon: "success"
                    });
                    window.location.href = '{{ route('roles.index') }}';
                }
            });
        }
    </script>
@stop
