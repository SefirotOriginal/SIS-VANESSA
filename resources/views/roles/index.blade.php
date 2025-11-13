@extends('adminlte::page')

@section('title', 'Consultar Roles')

@php
if (!function_exists('getPermissionBadgeClass')) {
    function getPermissionBadgeClass($permissionName) {
        $action = last(explode('.', $permissionName));
        switch ($action) {
            case 'create': case 'store': return 'bg-success';
            case 'index': case 'view': case 'show': case 'read': return 'bg-info text-dark';
            case 'edit': case 'update': return 'bg-warning text-dark';
            case 'delete': case 'destroy': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }
}
@endphp

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><b>Roles</b></h1>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="fas fa-user-shield"></i> Crear rol
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid px-0" style="max-height: calc(100vh - 150px); overflow-y: auto;">

        @if(session('success'))
            {{-- El JS al final se encargará de mostrar esto --}}
        @endif

        <div class="row">
            @forelse ($roles as $role)
                <div class="col-md-6 mb-3">
                    <div class="card shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Perfil"
                                    class="rounded-circle me-3" width="60" height="60">
                                <div class="d-flex flex-column">
                                    <h5 class="card-title mb-1"><b>{{ $role->name }}</b></h5>
                                    <span class="text-muted small">{{ $role->guard_name }}</span>
                                </div>
                            </div>

                            <div class="card-text mb-1">
                                <small>
                                    <b>Permisos:</b>
                                    @if($role->permissions->isEmpty())
                                        <span class="text-muted">Sin permisos asignados</span>
                                    @else
                                        <details class="permission-details">
                                            <summary>
                                                {{ $role->permissions->count() }} permisos asignados (clic para ver)
                                            </summary>
                                            <div class="permission-list-expanded mt-2">
                                                @foreach($role->permissions->sortBy('name') as $permission)
                                                    <span class="badge {{ getPermissionBadgeClass($permission->name) }} me-1 mb-1">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </small>
                            </div>
                            
                            <div class="mt-auto d-flex justify-content-end gap-2 pt-3">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                @if($role->name !== 'Super Admin' && $role->name !== 'Admin')
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" id="formEliminar{{ $role->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" title="Eliminar" onclick="confirmarEliminacion({{ $role->id }})">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">No hay roles registrados.</p>
                </div>
            @endforelse
        </div>
    </div>
@stop

@section('css')
    <style>
        html, body { height: 100%; overflow: hidden;}
        .content-wrapper { background-color: #f1f1f1; }
        .card-body { background-color: #ffffff; }

        .permission-details > summary {
            cursor: pointer;
            color: #007bff;
            list-style: none;
            display: inline-block;
            font-weight: bold;
        }
        .permission-details > summary::-webkit-details-marker {
            display: none;
        }
        .permission-details > summary::before {
            content: '► ';
            font-size: 0.8em;
            color: #6c757d;
        }
        .permission-details[open] > summary::before {
            content: '▼ ';
        }

        .permission-list-expanded {
            max-height: 150px;
            overflow-y: auto;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 0.5rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar' + id).submit();
                }
            });
        }

        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    </script>
@stop