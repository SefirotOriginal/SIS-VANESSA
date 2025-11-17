<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo - Folio #{{ $venta->referenceNumber }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .container {
            width: 100%;
            max-width: 280px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
            float: right;
            width: 200px;
        }
        .totals p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Farmacia Vanessa</h2>
        <p>Recibo de Venta - Folio: <strong>#{{ $venta->referenceNumber }}</strong></p>
        <p>Fecha: {{ $venta->created_at->format('d-m-Y H:i:s') }}</p>
        <p>Atendió: {{ $venta->usuario->name ?? 'N/A' }}</p>

        <h3>Productos</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                <tr>
                    <td>
                        {{ $detalle->productPresentation->product->name ?? 'N/A' }}
                        ({{ $detalle->productPresentation->presentation->name ?? '' }})
                    </td>
                    <td>{{ $detalle->quantity }}</td>
                    <td>${{ number_format($detalle->price, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p><strong>Total:</strong> <span>${{ number_format($venta->amountTotal, 2) }}</span></p>
            <p>Recibido: <span>${{ number_format($venta->amountPayment, 2) }}</span></p>
            <p>Cambio: <span>${{ number_format($venta->amountExchange, 2) }}</span></p>
        </div>

        <div style="clear: both; margin-top: 50px; text-align: center;">
            <p>¡Gracias por su compra!</p>
        </div>
    </div>
</body>
</html>