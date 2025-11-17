<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo - Folio #{{ $venta->referenceNumber }}</title>
    
    <style>
        /* Define el ancho del papel. 80mm es común, 58mm es más angosto */
        @page {
            size: 80mm;
            margin: 0;
        }
        body {
            font-family: 'monospace', 'Courier New', Courier, mono;
            font-size: 10px; /* Tamaño de fuente pequeño para impresoras térmicas */
            line-height: 1.4;
            margin: 0;
            padding: 5px; /* Pequeño margen para que no se corte */
            width: 280px; /* Ancho fijo para 80mm */
        }
        .container {
            width: 100%;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        
        /* Línea separadora */
        hr.dashed {
            border: 0;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        /* Estructura del Item */
        .item {
            margin-bottom: 5px;
        }
        .item-name {
            /* El nombre del producto ocupa su propia línea */
            margin-bottom: 2px;
        }
        .item-details {
            /* Flexbox para alinear 'Qty x Precio' a la izq.uierda y 'Subtotal' a la derecha */
            display: flex;
            justify-content: space-between;
        }
        
        /*Estructura de Totales */
        .totals-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .footer {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        
        {{-- Encabezado --}}
        <div class="header center">
            <h2 style="margin: 0;">Farmacia Vanessa</h2>
            <p style="margin: 0;">Recibo de Venta</p>
            <hr class="dashed">
            <p style="margin: 0;">Folio: <strong>#{{ $venta->referenceNumber }}</strong></p>
            <p style="margin: 0;">Fecha: {{ $venta->created_at->format('d-m-Y H:i:s') }}</p>
            <p style="margin: 0;">Atendió: {{ $venta->usuario->name ?? 'N/A' }}</p>
        </div>

        <hr class="dashed">

        {{-- Cuerpo (Productos) --}}
        <div class="items">
            @foreach($venta->detalles as $detalle)
            <div class="item">
                {{-- Nombre del producto --}}
                <div class="item-name">
                    {{ $detalle->productPresentation->product->name ?? 'N/A' }}
                    ({{ $detalle->productPresentation->presentation->name ?? '' }})
                </div>
                {{-- Cantidad, Precio y Subtotal --}}
                <div class="item-details">
                    <span>{{ $detalle->quantity }} x ${{ number_format($detalle->price, 2) }}</span>
                    <span class="bold">${{ number_format($detalle->subtotal, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <hr class="dashed">

        {{-- Totales --}}
        <div class="totals">
            <div class="totals-line">
                <span>Total:</span>
                <span class="bold">${{ number_format($venta->amountTotal, 2) }}</span>
            </div>
            <div class="totals-line">
                <span>Recibido:</span>
                <span>${{ number_format($venta->amountPayment, 2) }}</span>
            </div>
            <div class="totals-line">
                <span>Cambio:</span>
                <span>${{ number_format($venta->amountExchange, 2) }}</span>
            </div>
        </div>
        
        {{-- Pie del recibo --}}
        <div class="footer center">
            <p>¡Gracias por su compra!</p>
        </div>
        
    </div>
</body>
</html>