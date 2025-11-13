@extends('adminlte::page')

@section('title', 'Editar Rol')

@section('content_header')
    <h1><b>Editar rol</b></h1>
@stop

@section('content')
    {{-- Función Helper de PHP para los badges de color --}}
    @php
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
    @endphp

    <div class="container-fluid">
        <div class="card shadow w-100">
            {{-- Formulario principal para ACTUALIZAR --}}
            <form id="formEditarRol" action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label"><b>Nombre del rol</b></label>
                        {{-- Se rellena el valor del rol actual --}}
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ $role->name }}" required>
                        <input type="hidden" name="guard_name" value="{{ $role->guard_name ?? 'web' }}">
                    </div>

                    {{-- INICIO DE SECCIÓN DE PERMISOS (Idéntica a create.blade.php) --}}
                    <div class="mb-3">
                        <label class="form-label"><b>Permisos</b></label>
                        <div class="d-flex justify-content-end mb-2 gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                <i class="fas fa-check-double"></i> Seleccionar Todo
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn">
                                <i class="fas fa-times"></i> Deseleccionar Todo
                            </button>
                        </div>

                        @php $current_group = ''; @endphp
                        {{-- Usamos la variable $permissions que pasaste a la vista --}}
                        @foreach ($permissions->sortBy('name') as $permission)
                            @php
                                $parts = explode('.', $permission->name);
                                $group_name = count($parts) > 1 ? $parts[0] : 'General';
                            @endphp

                            @if ($group_name !== $current_group)
                                @if ($current_group !== '')
                                    </div></div></div> 
                                @endif
                                @php $current_group = $group_name; @endphp
                                
                                {{-- Espaciado mb-4 igual que en "Crear" --}}
                                <div class="card mb-4 shadow-sm permission-group">
                                    <div class="card-header bg-light py-2">
                                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                            <span class="text-capitalize fw-bold">
                                                {{ Str::replace(['_', '-'], ' ', $current_group) }}
                                            </span>
                                            <button type="button" class="btn btn-xs btn-outline-info" onclick="toggleGroupCheckboxes(this)">
                                                Sel. Grupo
                                            </button>
                                        </h5>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="permission-list-columns">
                            @endif
                            
                            <div class="form-check form-switch d-flex justify-content-between align-items-center pe-2">
                                <div>
                                    {{-- 
                                        CAMBIO CLAVE:
                                        Se añade la lógica 'checked' para marcar los permisos que el rol YA TIENE
                                    --}}
                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                                        value="{{ $permission->name }}" id="perm_{{ $permission->id }}"
                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                                @php
                                    $action = last(explode('.', $permission->name));
                                    $badgeClass = getPermissionBadgeClass($permission->name);
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ Str::ucfirst($action) }}</span>
                            </div>

                        @endforeach
                        
                        @if ($current_group !== '')
                            </div></div></div> {{-- Cierra el último grupo --}}
                        @endif
                    </div>
                    {{-- FIN DE SECCIÓN DE PERMISOS --}}

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <div>
                            {{-- Botón para disparar la alerta de eliminación --}}
                            <button type="button" onclick="confirmarEliminacion({{ $role->id }})" class="btn btn-danger me-2">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                            {{-- Botón para disparar la alerta de guardado --}}
                            <button type="button" class="btn btn-success" id="btnGuardar">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Formulario separado para la eliminación (igual que en index) --}}
            <form id="formEliminar{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- CSS Limpio (Idéntico a create.blade.php) --}}
    <style>
        .content-wrapper { background-color: #f1f1f1; }
        .card-body { background-color: #ffffff; }
        .permission-list-columns { column-count: 3; column-gap: 1.5rem; }
        .form-check { break-inside: avoid; page-break-inside: avoid; }
        .form-check-label {
            max-width: 200px; 
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }
        @media (max-width: 992px) { .permission-list-columns { column-count: 2; } }
        @media (max-width: 768px) {
            .permission-list-columns { column-count: 1; }
            .form-check-label { max-width: none; } 
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Lógica de SweetAlert para GUARDAR
            const btnGuardar = document.getElementById('btnGuardar');
            if(btnGuardar) {
                btnGuardar.addEventListener('click', function (e) {
                    const form = document.getElementById('formEditarRol');
                    
                    if (form.name.value.trim() === '') {
                         Swal.fire("Error", "El nombre del rol es obligatorio.", "error");
                         form.name.focus();
                         return;
                    }
                    e.preventDefault(); 
                    Swal.fire({
                        title: "¿Guardar los cambios?", // Texto cambiado
                        text: "Se actualizará el rol con los permisos seleccionados.", // Texto cambiado
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: "Sí, guardar", // Texto cambiado
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            // Lógica de "Seleccionar Todo" (Idéntica a create.blade.php)
            const selectAllBtn = document.getElementById('selectAllBtn');
            if(selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
                });
            }

            // Lógica de "Deseleccionar Todo" (Idéntica a create.blade.php)
            const deselectAllBtn = document.getElementById('deselectAllBtn');
            if(deselectAllBtn) {
                deselectAllBtn.addEventListener('click', function() {
                    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
                });
            }
        });

        // Lógica de "Sel. Grupo" (Idéntica a create.blade.php)
        function toggleGroupCheckboxes(button) {
            const cardBody = button.closest('.card').querySelector('.card-body');
            const checkboxes = cardBody.querySelectorAll('.permission-checkbox');
            const shouldCheck = !Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = shouldCheck);
            button.textContent = shouldCheck ? 'Desel. Grupo' : 'Sel. Grupo';
        }

        // Lógica de SweetAlert para ELIMINAR (Idéntica a index.blade.php)
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
    </script>
@stop