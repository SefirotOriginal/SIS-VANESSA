@extends('adminlte::page')

@section('title', 'Crear Corte de Caja')

@section('content_header')
    <h1><b>Registro de corte de caja</b></h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card shadow w-100">
        <div class="card-body">
            <div class="row">
                {{-- Fecha del corte --}}
                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label"><b>Fecha</b></label>
                    <input type="date" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                </div>

                {{-- Hora del corte --}}
                <div class="col-md-6 mb-3">
                    <label for="hora" class="form-label"><b>Hora</b></label>
                    <input type="time" id="hora" class="form-control" value="{{ date('H:i') }}" disabled>
                </div>
            </div>

            <div class="row">
                {{-- Monto inicial en caja --}}
                <div class="col-md-6 mb-3">
                    <label for="monto_inicial" class="form-label"><b>Monto inicial en caja</b></label>
                    <input type="number" id="monto_inicial" class="form-control" placeholder="Ej. 1000.00">
                </div>

                {{-- Total de ventas --}}
                <div class="col-md-6 mb-3">
                    <label for="total_ventas" class="form-label"><b>Total de ventas registradas</b></label>
                    <input type="number" id="total_ventas" class="form-control" placeholder="Ej. 3540.50">
                </div>
            </div>

            <div class="row">
                {{-- Ingresos extra --}}
                <div class="col-md-6 mb-3">
                    <label for="ingresos_extra" class="form-label"><b>Otros ingresos</b></label>
                    <input type="number" id="ingresos_extra" class="form-control" placeholder="Ej. 200.00">
                </div>

                {{-- Egresos --}}
                <div class="col-md-6 mb-3">
                    <label for="egresos" class="form-label"><b>Egresos</b></label>
                    <input type="number" id="egresos" class="form-control" placeholder="Ej. 150.00">
                </div>
            </div>

            {{-- Observaciones --}}
            <div class="mb-3">
                <label for="observaciones" class="form-label"><b>Observaciones</b></label>
                <textarea id="observaciones" class="form-control" rows="4" placeholder="Escriba alguna observación si lo desea..."></textarea>
            </div>

            {{-- Botones --}}
            <div class="d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button onclick="alertaConfirmar()" type="button" class="btn btn-success">
                    <i class="fas fa-cash-register"></i> Generar Corte
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    html, body {
        height: 100%;
        overflow-x: hidden;
    }
    .content-wrapper {
        background-color: #f1f1f1;
    }
    .card-body {
        background-color: #ffffff;
    }
    .form-label {
        font-weight: bold;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Ventas
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: ['01', '05', '10', '15', '20', '25', '30'],
            datasets: [{
                label: 'Ingresos diarios',
                data: [200, 500, 700, 400, 650, 900, 750],
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
                tension: 0.3
            }]
        }
    });

    // Gráfico de Estadísticas
    const statsCtx = document.getElementById('estadisticasChart').getContext('2d');
    new Chart(statsCtx, {
        type: 'bar',
        data: {
            labels: ['Producto A', 'Producto B', 'Producto C', 'Producto D'],
            datasets: [{
                label: 'Unidades Vendidas',
                data: [120, 90, 60, 30],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
            }]
        }
    });
</script>
<script>
    function alertaConfirmar() {
        Swal.fire({
        title: "¿Registrar el corte de caja?",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Registrar",
        cancelButtonText: "Cancelar"
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            Swal.fire("¡Corte de caja registrado!", "", "success");
            // Redirige a la ruta deseada
            window.location.href = 'consultas';
        }
        });
    }
</script>
@stop