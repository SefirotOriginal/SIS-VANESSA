@extends('adminlte::page')

@section('title', 'Consultar Usuarios')

@section('content')
    <div class="container-fluid" style="max-height: 75vh; overflow-y: auto;">
        <div class="content-header">
            <div class="container-fluid px-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="m-0"><b>Consulta de usuarios</b></h1>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Crear usuario
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($users as $user)
                <div class="col-md-6 mb-3">
                    <div class="card shadow">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Perfil"
                                    class="rounded-circle me-3" width="60" height="60">
                                <div class="d-flex flex-column">
                                    <h5 class="card-title mb-2"><b>{{ $user->name }}</b></h5>
                                    <span class="text-muted">{{ $user->roles->pluck('name')->join(', ') ?: 'Sin rol' }}</span>
                                </div>
                            </div>
                            <p class="card-text"><b>Correo:</b> {{ $user->email }}</p>
                            <p class="card-text"><b>Número telefónico:</b> {{ $user->phoneNumber ?? 'No registrado' }}</p>
                            <p class="card-text"><b>Fecha de registro:</b> {{ $user->created_at->format('d-m-Y') }}</p>
                            <p class="card-text"><b>Estado:</b>
                                <span class="badge {{ $user->activo ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>
                            <div class="mt-auto d-flex justify-content-end">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary"
                                    title="Editar">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
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

        .custom-header {
            background-color: #0077B6;
            color: white;
        }
    </style>
    <style>
        .custom-header th {
            white-space: nowrap;
            /* Evita que el texto se divida */
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script>
        new DataTable('#usuarioConsulta', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            layout: {
                top2End: function() {
                    // Create a div for the toolbar
                    let toolbar = document.createElement('div');
                    toolbar.className = 'd-flex align-items-center mb-3';
                    // Create the "Create Product" button
                    let createButton = document.createElement('a');
                    createButton.href = "{{ route('productos.creacion') }}"; // Ensure this route is correct
                    createButton.className = 'btn btn-primary ml-2'; // Add margin-left for spacing
                    createButton.innerText = 'Crear producto';
                    // Append the button to the toolbar
                    toolbar.appendChild(createButton);
                    return toolbar; // Return the custom toolbar
                },
                topStart: 'search',
                topEnd: null,
                bottomStart: [{
                        paging: {
                            div: {
                                className: 'layout-start',
                                text: 'top2 A'
                            }
                        }
                    },
                    {
                        info: {
                            div: {
                                className: 'layout-full',
                                text: 'top2 B'
                            }
                        }
                    }
                ],
                bottomEnd: null
            }
        });
    </script>
    <script>
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
                        text: "El producto se ha eliminado de la tabla.",
                        icon: "success"
                    });
                }
            });
        }
    </script>
@stop
