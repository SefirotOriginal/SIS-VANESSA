@extends('adminlte::page')

@section('title', 'Laboratorios')

@section('content_header')
<h1><b>Laboratorios</b></h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="row mt-4">
            <a href="{{route('laboratories.create')}}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i>Crear Laboratorio
            </a>

            <div class="table-responsive">
                <table id="batchesTable" class="table table-striped text-center" style="width:100%">
                    <thead class="custom-header">
                        <tr>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Contacto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="batchesTableBody">
                        @foreach ( $laboratories as $laboratory)
                        <tr>
                            <td>{{ $laboratory->name }}</td>
                            <td>{{ $laboratory->state }}</td>
                            <td>{{ $laboratory->contact }}</td>
                            <td>
                                <a href="{{ route('laboratories.show', $laboratory) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('laboratories.edit', $laboratory) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('laboratories.destroy', $laboratory) }}" method="POST" class="d-inline" id="formEliminar{{ $category->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{ $category->id }})">
                                        <i class="fas fa-trash  "></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
<style>
    html, body {
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
    .custom-header th {
        white-space: nowrap;
    }
    .card-body {
        padding-top: 0;
        padding-bottom: 1rem;
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
    $(document).ready(function () {
        $('#batchesTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            layout: {
                topStart: 'search',
                topEnd: null,
                bottomStart: [
                    {
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
