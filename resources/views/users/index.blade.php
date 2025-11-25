@extends('adminlte::page')

@section('title', 'Consultar Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><b>Usuarios</b></h1>
        @can('usuarios.create')
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Crear Usuario
            </a>
        @endcan
    </div>
@stop

@section('content')
    <div class="container-fluid px-0" style="max-height: calc(100vh - 150px); overflow-y: auto;">

        @if (session('success'))
            {{-- El JS al final se encargará de mostrar esto --}}
        @endif

        <div class="row">
            @forelse ($users as $user)
                <div class="col-md-6 mb-3">
                    <div class="card shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $user->adminlte_image() }}" alt="Perfil" class="rounded-circle me-3"
                                    width="60" height="60" style="object-fit: cover;">

                                <div class="d-flex flex-column">
                                    <h5 class="card-title mb-1"><b>{{ $user->name }}</b></h5>
                                    <span
                                        class="text-muted small">{{ $user->roles->pluck('name')->join(', ') ?: 'Sin rol' }}</span>
                                </div>
                            </div>
                            <p class="card-text mb-1"><small><b>Correo:</b> {{ $user->email }}</small></p>
                            <p class="card-text mb-1"><small><b>Teléfono:</b>
                                    {{ $user->phoneNumber ?? 'No registrado' }}</small></p>
                            <p class="card-text mb-1"><small><b>Registro:</b>
                                    {{ $user->created_at->format('d/m/Y') }}</small></p>

                            <p class="card-text mb-3"><b>Estado:</b>
                                <span class="badge {{ $user->email_verified_at ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->email_verified_at ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>

                            @if (auth()->id() !== $user->id)
                                <div class="mt-auto d-flex justify-content-end gap-2">
                                    @can('usuarios.edit')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"
                                            title="Editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    @endcan

                                    @can('usuarios.destroy')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                            id="formEliminar{{ $user->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" title="Eliminar"
                                                onclick="confirmarEliminacion({{ $user->id }})">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">No hay usuarios registrados.</p>
                </div>
            @endforelse
        </div>
    </div>
@stop

@section('css')
    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función estándar para confirmar eliminación
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

        // Script estándar para notificación toast
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
