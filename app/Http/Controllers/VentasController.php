<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\ProductPresentation;
use App\Models\Batch;
use App\Models\VentaDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentasController extends Controller
{
    public function consultas()
    {
        $productos = Producto::all();
        $ventas = Venta::with('usuario')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('ventas.consulta', compact('ventas'));
    }

    public function crear(Request $request)
    {
        $validated = $request->validate([
            'receiptType' => 'required|string',
            'amountPayment' => 'required|numeric|min:0', // Total a Pagar
            'amountTotal' => 'required|numeric|min:0',   // Cantidad Recibida
            'amountExchange' => 'required|numeric',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:product_presentations,id', // Valida que el ID exista
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // Si algo falla se revierte todo
        DB::beginTransaction();

        try {
            // Crea el registro principal de la Venta
            do {
                $folio = random_int(100000, 999999); // genera un número de 6 dígitos
            } while (Venta::where('referenceNumber', $folio)->exists());

            $venta = Venta::create([
                'user_id' => auth()->id(),
                'referenceNumber' => $folio,
                'receiptType' => $validated['receiptType'],
                'amountPayment' => $validated['amountPayment'],
                'amountTotal' => $validated['amountTotal'],
                'amountExchange' => $validated['amountExchange'],
            ]);

            // Procesar cada producto, guardar detalle y descontar stock
            foreach ($validated['products'] as $item) {
                $cantidadVender = (int)$item['quantity'];
                
                // Se busca la presentación y sus lotes con stock (FEFO: First-to-Expire, First-Out)
                $presentation = ProductPresentation::find($item['id']);
                $lotes = $presentation->batches()
                                    ->where('stock', '>', 0)
                                    ->orderBy('expiration_date', 'asc') // FEFO
                                    ->get();

                $stockTotal = $lotes->sum('stock');
                if ($stockTotal < $cantidadVender) {
                    throw new \Exception("Stock insuficiente para: " . $presentation->product->name);
                }

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'product_presentation_id' => $item['id'],
                    'quantity' => $cantidadVender,
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $cantidadVender,
                ]);

                // Descontar el stock de los lotes (lógica FEFO)
                $cantidadFaltante = $cantidadVender;
                foreach ($lotes as $lote) {
                    if ($cantidadFaltante <= 0) {
                        break;
                    }

                    $descontar = min($lote->stock, $cantidadFaltante);
                    $lote->stock -= $descontar;
                    $lote->save();

                    $cantidadFaltante -= $descontar;
                }
            }

            DB::commit();
            return redirect()->route('ventas.consulta')->with('success', 'Venta registrada exitosamente. Folio: ' . $folio);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al registrar venta: " . $e->getMessage());
            return back()->withErrors(['error' => 'Error al procesar la venta: ' . $e->getMessage()])->withInput();
        }
    }

    public function detalles($id)
    {
        // Se busca la venta por su ID con with() para cargar las relaciones en la vista de detalles
        $venta = Venta::with([
            'usuario', // Atendió
            'detalles', // Productos
            'detalles.productPresentation.product', // Nombre del producto
            'detalles.productPresentation.presentation' // Nombre de la presentación
        ])->findOrFail($id);

        return view('ventas.detalles', compact('venta'));
    }

    // Muestra la vista para confirmar una devolución
    public function devoluciones($id)
    {
        $venta = Venta::with([
            'usuario',
            'detalles',
            'detalles.productPresentation.product',
            'detalles.productPresentation.presentation'
        ])->findOrFail($id);

        return view('ventas.devoluciones', compact('venta'));
    }

    // Se procesa la devolución de una venta completa
    public function procesarDevolucion(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $venta = Venta::with('detalles')->findOrFail($id);

            // Se recorre cada producto de la venta para devolver el stock
            foreach ($venta->detalles as $detalle) {
                $cantidadDevuelta = $detalle->quantity;
                $presentacion = ProductPresentation::find($detalle->product_presentation_id);

                if ($presentacion) {
                    $lote = $presentacion->batches()
                                        ->orderBy('expiration_date', 'desc')
                                        ->first();
                    if ($lote) {
                        $lote->stock += $cantidadDevuelta;
                        $lote->save();
                    } else {
                        throw new \Exception("No se encontró un lote para devolver el stock de " . $presentacion->id);
                    }
                }
            }

            /*
            Se marca la venta como "eliminada" (Soft Delete).
            La venta desaparecerá de la lista de 'consulta' pero se
            mantendrá en la base de datos para historial
            */
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.consulta')->with('success', 'Venta devuelta y stock restaurado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al devolver venta: " . $e->getMessage());
            return back()->withErrors(['error' => 'Error al procesar la devolución: ' . $e->getMessage()]);
        }
    }
}
