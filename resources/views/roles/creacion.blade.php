@extends('adminlte::page')

@section('title', 'Crear Rol')

@section('content_header')
    <h1><b>Registro de roles</b></h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card shadow w-100">
        <div class="card-body">
            <div class="mb-3">
                <label for="nombre" class="form-label"><b>Nombre del rol</b></label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Administrador" required>
            </div>
            <div class="mb-3">
                <label for="permisos" class="form-label"><b>Permisos</b></label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="usuarios" id="perm_usuarios">
                    <label class="form-check-label" for="perm_usuarios">Gestionar usuarios</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="inventario" id="perm_inventario">
                    <label class="form-check-label" for="perm_inventario">Consultar y modificar inventario</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="reportes" id="perm_reportes">
                    <label class="form-check-label" for="perm_reportes">Acceder a reportes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="configuracion" id="perm_configuracion">
                    <label class="form-check-label" for="perm_configuracion">Configurar sistema</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="configuracion" id="perm_configuracion">
                    <label class="form-check-label" for="perm_configuracion">Consultar logs del sistema</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="usuarios" id="perm_usuarios">
                    <label class="form-check-label" for="perm_configuracion">Ver historial de usuarios</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="configuracion" id="perm_configuracion">
                    <label class="form-check-label" for="perm_configuracion">Actualizar configuración técnica</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="ventas" id="perm_ventas">
                    <label class="form-check-label" for="perm_configuracion">Registrar ventas</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="inventario" id="perm_inventario">
                    <label class="form-check-label" for="perm_configuracion">Consultar inventario</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permisos" value="reportes" id="perm_reportes">
                    <label class="form-check-label" for="perm_configuracion">Consultar reportes de ventas</label>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button onclick="alertaConfirmar()" type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Rol
                </button>
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
    <script>
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
                window.location.href = 'consultas';
            }
            });
        }
    </script>
@stop