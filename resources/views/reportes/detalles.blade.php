@extends('adminlte::page')

@section('title', 'Visualizar Reporte')

@section('content_header')
    <h1><b>Visualización de reporte</b></h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card shadow w-100">
        <div class="card-body">
            {{-- Datos principales --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label"><b>Fecha inicial</b></label>
                    <input type="date" class="form-control" value="2025-01-01" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label"><b>Fecha final</b></label>
                    <input type="date" class="form-control" value="2025-01-31" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label"><b>Tipo de reporte</b></label>
                    <select class="form-control" disabled>
                        <option selected>Ventas</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label"><b>Descripción del reporte</b></label>
                <textarea class="form-control" rows="4" readonly>
Reporte de ventas correspondiente al mes de enero de 2025. Incluye resumen diario de ingresos, productos más vendidos y estadísticas comparativas respecto al mes anterior.
                </textarea>
            </div>

            {{-- Gráficos --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label"><b>Gráfico de Ventas</b></label>
                    <canvas id="ventasChart"></canvas>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><b>Gráfico de Estadísticas</b></label>
                    <canvas id="estadisticasChart"></canvas>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label"><b>Recomendaciones</b></label>
                <textarea class="form-control" rows="3" readonly>
Considera incrementar el inventario de productos con mayor rotación y revisar promociones para los menos vendidos. Ajustar precios si es necesario para maximizar beneficios.
                </textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button onclick="alertaExportar()" type="button" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar como PDF
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
    function alertaExportar() {
        Swal.fire("¡Archivo exportado!");
    }
</script>
@stop