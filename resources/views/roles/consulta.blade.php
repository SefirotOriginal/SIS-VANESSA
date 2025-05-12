@extends('adminlte::page')

@section('title', 'Consultar Roles')

@section('content')
<div class="container-fluid" style="max-height: 75vh; overflow-y: auto;">
    <div class="content-header">
        <div class="container-fluid px-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="m-0"><b>Consulta de roles</b></h1>
                <a href="creacion" class="btn btn-primary">
                    <i class="fas fa-user-shield"></i> Crear rol
                </a>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card shadow w-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="card-title m-0"><b>Administrador</b></h5>
                </div>
                <p class="card-text"><b>Permisos:</b></p>
                <ul class="mb-3">
                    <li>Gestionar usuarios</li>
                    <li>Consultar y modificar inventario</li>
                    <li>Acceder a reportes</li>
                    <li>Configurar sistema</li>
                </ul>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('roles.edicion', 1) }}" class="btn btn-sm btn-primary">Editar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card shadow w-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="card-title m-0"><b>Soporte</b></h5>
                </div>
                <p class="card-text"><b>Permisos:</b></p>
                <ul class="mb-3">
                    <li>Consultar logs del sistema</li>
                    <li>Ver historial de usuarios</li>
                    <li>Actualizar configuración técnica</li>
                </ul>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('roles.edicion', 3) }}" class="btn btn-sm btn-primary">Editar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="card shadow w-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="card-title m-0"><b>Vendedor</b></h5>
                </div>
                <p class="card-text"><b>Permisos:</b></p>
                <ul class="mb-3">
                    <li>Registrar ventas</li>
                    <li>Consultar inventario</li>
                    <li>Consultar reportes de ventas</li>
                </ul>
                <div class="d-flex justify-content-end">
                    <a href="edicion" class="btn btn-sm btn-primary">Editar</a>
                </div>
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
    .content-wrapper{
        background-color:#f1f1f1;
    }
    .card-body{
        background-color:#ffffff;
    }
    .custom-header {
        background-color: #0077B6;
        color: white;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"> </script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"> </script>
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@stop