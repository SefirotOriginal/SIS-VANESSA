@extends('adminlte::page')

@section('title', 'Consultar Roles')

@section('content')
    <div class="container-fluid" style="max-height: 75vh; overflow-y: auto;">
        <div class="content-header">
            <div class="container-fluid px-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="m-0"><b>Consulta de roles</b></h1>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-shield"></i> Crear rol
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="card shadow w-100">
                <div class="card-body">
                    @foreach ($roles as $role)
                        <div class="d-flex align-items-center mb-3 col-md-12">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Perfil"
                                class="rounded-circle me-3" width="60" height="60">
                            <div class="d-flex flex-column">
                                <h5 class="card-title mb-2"><b>{{ $role->name }}</b></h5>
                                <span class="text-muted">{{ $role->guard_name }}</span>
                            </div>
                        </div>
                        {{-- <div class="d-flex justify-content-end"> --}}
                        <div class="colum justify-content-end">
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary me-2">Editar</a>
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline" class="delete-role-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-danger me-2"><i class="fa-solid fa-trash"></i>
                                    Delete</button>
                                    <p></p>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
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
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-role-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault(); // Previene el envío inmediato del formulario

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esta acción!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, ¡eliminar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Si el usuario confirma, se envía el formulario
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
@stop
