@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>Reporte de Ventas y Predicciones</h1>
@stop

@section('content')
    <div class="container">
        <!-- Formulario para seleccionar fechas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Seleccionar Rango de Fechas</h3>
            </div>
            <div class="card-body">
                <form id="reportForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Fecha de Inicio:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">Fecha de Fin:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">Generar Reporte</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contenedor para el reporte -->
        <div id="reportContainer" style="display: none;">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reporte Generado</h3>
                    <div class="card-tools">
                        <button id="downloadPdf" class="btn btn-success btn-sm">Descargar PDF</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabla de Ventas -->
                    <div class="mb-4">
                        <h4>Datos de Ventas en el Rango Seleccionado</h4>
                        <div class="table-responsive">
                            <table id="salesTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Referencia</th>
                                        <th>Usuario</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Detalles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Datos se llenarán dinámicamente -->
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Ventas Reales</h4>
                            <canvas id="salesChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h4>Predicciones de Ventas</h4>
                            <canvas id="predictionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spinner de carga -->
        <div id="loading" style="display: none;" class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Generando reporte...</span>
            </div>
            <p>Generando reporte y predicciones...</p>
        </div>
    </div>
@stop

@section('css')
    <style>
        #reportContainer {
            margin-top: 20px;
        }

        canvas {
            max-width: 100%;
            height: 300px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Mostrar spinner
            document.getElementById('loading').style.display = 'block';
            document.getElementById('reportContainer').style.display = 'none';

            // Petición AJAX
            fetch('{{ route('reports.predictive.sales') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        start_date: startDate,
                        end_date: endDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    if (data.error) {
                        alert('Error: ' + data.error);
                    } else {
                        displayReport(data);
                    }
                })
                .catch(error => {
                    document.getElementById('loading').style.display = 'none';
                    console.error('Error:', error);
                    alert('Error al generar el reporte');
                });
        });

        function displayReport(data) {
            document.getElementById('reportContainer').style.display = 'block';

            // Llenar tabla de ventas
            const tbody = document.querySelector('#salesTable tbody');
            tbody.innerHTML = ''; // Limpiar tabla
            data.sales.list.forEach(sale => {
                const row = document.createElement('tr');
                const detailsHtml = sale.details.map(detail =>
                    `<div><strong>${detail.product}</strong> - Cant: ${detail.quantity}, Precio: ${detail.price}, Sub: ${detail.subtotal}</div>`
                ).join('');
                row.innerHTML = `
                    <td>${sale.id}</td>
                    <td>${sale.referenceNumber}</td>
                    <td>${sale.user}</td>
                    <td>${sale.amountTotal}</td>
                    <td>${sale.created_at}</td>
                    <td>${detailsHtml}</td>
                `;
                tbody.appendChild(row);
            });

            // Gráfico de ventas reales
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: data.sales.labels,
                    datasets: [{
                        label: 'Ventas Reales',
                        data: data.sales.data,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de predicciones
            const predictionCtx = document.getElementById('predictionChart').getContext('2d');
            new Chart(predictionCtx, {
                type: 'line',
                data: {
                    labels: data.predictions.labels,
                    datasets: [{
                        label: 'Predicciones',
                        data: data.predictions.data,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        document.getElementById('downloadPdf').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF('landscape');

            html2canvas(document.getElementById('reportContainer')).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgWidth = 280;
                const pageHeight = 210;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;

                let position = 10;

                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save('reporte_ventas_predicciones.pdf');
            });
        });
    </script>
@stop
