@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>Reporte de Ventas y Predicciones</h1>
@stop

@section('content')
<div class="container">

    {{-- FORMULARIO PARA SELECCIONAR FECHAS --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Seleccionar Rango de Fechas</h3>
        </div>
        <div class="card-body">

            <form id="reportForm">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">Fecha de Inicio:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label for="end_date">Fecha de Fin:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary btn-block">Generar Reporte</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{-- CONTENEDOR DEL REPORTE --}}
    <div id="reportContainer" style="display: none;">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reporte Generado</h3>
                <button id="downloadPdf" class="btn btn-success btn-sm float-right">
                    Descargar PDF
                </button>
            </div>

            <div class="card-body" id="reportContent">

                {{-- ENCABEZADO --}}
                <div style="padding: 20px; border-bottom: 2px solid #333; margin-bottom: 20px;">
                    <h2 style="margin: 0; font-size: 24px; font-weight: bold;">Farmacia Vanessa</h2>
                    <p id="report-dates" style="margin: 0; font-size: 14px; color: #555;">
                        <!-- JS insertará las fechas -->
                    </p>
                </div>

                {{-- KPIs --}}
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="padding: 10px; background: #eef6ff; border-radius: 8px; width: 200px;">
                        <strong>Total de ventas:</strong><br>
                        <span id="kpi_total_ventas">—</span>
                    </div>

                    <div style="padding: 10px; background: #eafff3; border-radius: 8px; width: 200px;">
                        <strong>Cantidad de operaciones:</strong><br>
                        <span id="kpi_num_operaciones">—</span>
                    </div>

                    <div style="padding: 10px; background: #fff8e6; border-radius: 8px; width: 200px;">
                        <strong>Ticket Promedio:</strong><br>
                        <span id="kpi_ticket_promedio">—</span>
                    </div>
                </div>

                {{-- REPORTE DE IA --}}
                <h3>Resumen del Comportamiento de Ventas</h3>
                <div id="geminiReport"
                     style="white-space: pre-line; padding: 10px; background: #f9f9f9; border-radius: 8px; border: 1px solid #ccc;">
                    <!-- Texto generado por IA -->
                </div>

                {{-- TABLA DE VENTAS --}}
                <h3 class="mt-4">Detalle de Ventas Registradas</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="salesTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Referencia</th>
                                <th>Usuario</th>
                                <th>Total</th>
                                <th>Fecha</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody id="sales_table_body">
                            <!-- JS insertará datos -->
                        </tbody>
                    </table>
                </div>

                {{-- GRÁFICAS --}}
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h4>Ventas Reales</h4>
                        <canvas id="salesChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h4>Predicción de Ventas (Gemini)</h4>
                        <canvas id="predictionChart"></canvas>
                    </div>
                </div>

                {{-- CONCLUSIÓN FINAL --}}
                <div style="margin-top: 40px; padding: 15px; background: #f3f7ff; border-left: 5px solid #0059ff;">
                    <h3>Conclusiones y Recomendaciones</h3>
                    <div id="gemini_conclusiones" style="white-space: pre-line; margin-top: 10px;">
                        <!-- IA completa -->
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- SPINNER --}}
    <div id="loading" class="text-center" style="display: none;">
        <div class="spinner-border" role="status"></div>
        <p>Generando reporte...</p>
    </div>

</div>
@stop

@section('css')
<style>
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
    const endDate   = document.getElementById('end_date').value;

    document.getElementById('loading').style.display = 'block';
    document.getElementById('reportContainer').style.display = 'none';

    fetch('{{ route('reports.predictive.sales') }}', {
        method : 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body   : JSON.stringify({ start_date: startDate, end_date: endDate })
    })
    .then(resp => resp.json())
    .then(data => {
        document.getElementById('loading').style.display = 'none';

        if (data.error) {
            alert(data.error);
        } else {
            displayReport(data, startDate, endDate);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Ocurrió un error.");
    });
});

function displayReport(data, startDate, endDate) {
    document.getElementById('reportContainer').style.display = 'block';

    // Insertar fechas
    document.getElementById('report-dates').innerHTML =
        `Período: ${startDate} — ${endDate}`;

    // KPIs
    const totalVentas = data.sales.data.reduce((a,b) => a + b, 0);
    const numVentas   = data.sales.list.length;
    const ticketProm  = (totalVentas / numVentas).toFixed(2);

    document.getElementById('kpi_total_ventas').innerText = `$${totalVentas}`;
    document.getElementById('kpi_num_operaciones').innerText = numVentas;
    document.getElementById('kpi_ticket_promedio').innerText = `$${ticketProm}`;

    // Texto de IA
    document.getElementById('geminiReport').innerText = data.report_text || "—";
    document.getElementById('gemini_conclusiones').innerText = data.report_text || "—";

    // Tabla
    let tbody = document.getElementById('sales_table_body');
    tbody.innerHTML = "";

    data.sales.list.forEach(sale => {
        let detalles = sale.details.map(d =>
            `<div><strong>${d.product}</strong> - Cant: ${d.quantity}, Precio: ${d.price}, Sub: ${d.subtotal}</div>`
        ).join("");

        tbody.innerHTML += `
            <tr>
                <td>${sale.id}</td>
                <td>${sale.referenceNumber}</td>
                <td>${sale.user}</td>
                <td>${sale.amountTotal}</td>
                <td>${sale.created_at}</td>
                <td>${detalles}</td>
            </tr>
        `;
    });

    // Graficas
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: data.sales.labels,
            datasets: [{
                label: 'Ventas Reales',
                data: data.sales.data,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });

    new Chart(document.getElementById('predictionChart'), {
        type: 'line',
        data: {
            labels: data.predictions.labels,
            datasets: [{
                label: 'Predicciones',
                data: data.predictions.data,
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        }
    });
}

document.getElementById('downloadPdf').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('landscape');

    html2canvas(document.getElementById('reportContent')).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        pdf.addImage(imgData, 'PNG', 5, 0, 285, 200);
        pdf.save('reporte_ventas.pdf');
    });
});

</script>
@stop
