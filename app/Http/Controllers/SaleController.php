<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Batch;
use App\Models\DetailSale;
use App\Models\ProductPresentation;
// use App\Models\DetailSale;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sales = Sale::with('user')
            ->orderBy('created_at', 'desc')
            ->get();;

        return view('admin.sale.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::all();
        $productPresentations = ProductPresentation::all();

        // Preparar la colección que la vista actual espera como `$productos`.
        $products = ProductPresentation::with(['product', 'presentation'])
            ->whereHas('product')
            ->whereHas('presentation')
            ->withSum('batches', 'stock')
            ->whereHas('batches', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->get();

        return view('admin.sale.create', compact('products', 'productPresentations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();

        try {
            //
            $sheet = random_int(100000, 999999);
            while (Sale::where('referenceNumber', $sheet)->exists()) {
                $sheet = random_int(100000, 999999);
            }
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'referenceNumber' => $sheet,
                'receiptType' => $request->input('receiptType'),
                'amountPayment' => $request->input('amountPayment'),
                'amountTotal' => $request->input('amountTotal'),
                'amountExchange' => $request->input('amountExchange'),
            ]);

            //
            foreach ($request->input('products') as $item) {
                $cantidadVender = (int)$item['quantity'];

                // Obtener los batchs con stock disponible, ordenados por fecha de vencimiento (FEFO)
                $batches = Batch::where('product_presentation_id', $item['id'])
                    ->where('stock', '>', 0)
                    ->orderBy('expiration_date', 'asc')
                    ->get();

                $stockTotal = $batches->sum('stock');
                if ($stockTotal < $cantidadVender) {
                    throw new \Exception("Stock insuficiente para el producto presentación ID: " . $item['id']);
                }

                // Crear registro de detalle de venta
                $presentation = ProductPresentation::find($item['id']);
                DetailSale::create([
                    'sale_id' => $sale->id,
                    'product_id' => $presentation->product_id,
                    'product_presentation_id' => $item['id'],
                    'salePrice' => $item['price'],
                    'quantityProduct' => $cantidadVender,
                    'subTotal' => $item['price'] * $cantidadVender,
                ]);

                // Descontar stock de los batchs (FEFO)
                $cantidadFaltante = $cantidadVender;
                foreach ($batches as $batch) {
                    if ($cantidadFaltante <= 0) {
                        break;
                    }
                    $descontar = min($batch->stock, $cantidadFaltante);
                    $batch->stock -= $descontar;
                    $batch->save();
                    $cantidadFaltante -= $descontar;
                }
            }


            DB::commit();
        } catch (\Exception $e) {
            Log::error('Error storing sale: ' . $e->getMessage());
            DB::rollBack();
            return back()->withErrors('Error processing sale. Please try again.');
        }

        $products = ProductPresentation::with(['product', 'presentation'])
            ->whereHas('product')
            ->whereHas('presentation')
            ->withSum('batches', 'stock')
            ->whereHas('batches', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->get();

        return redirect()->route('sales.create', compact('products'))->with('success', 'Sale created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //

        // Se busca la venta por su ID con with() para cargar las relaciones en la vista de detalles
        $sale = Sale::with([
            'user', // Atendió
            'details', // Productos
            'details.productPresentation.product', // Nombre del producto
            'details.productPresentation.presentation' // Nombre de la presentación
        ])->findOrFail($id);

        return view('admin.sale.details', compact('sale'));
    }

    public function print_receipt($id)
    {
        $sale = Sale::with([
            'user',
            'details',
            'details.productPresentation.product',
            'details.productPresentation.presentation'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.sale.receipt', compact('sale'));
        return $pdf->stream("receipt-{$sale->referenceNumber}.pdf");

        // Opcional: Para forzar la descarga
        // return $pdf->download("recibo-{$venta->referenceNumber}.pdf");
    }

    public function return_Sale($id)
    {
        //
        $sale = Sale::with([
            'user',
            'details',
            'details.productPresentation.product',
            'details.productPresentation.presentation'
        ])->findOrFail($id);

        return view('admin.sale.return', compact('sale'));
    }

    // Se procesa la devolución de una venta completa
    public function process_return(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::with('details')->findOrFail($id);

            // Se recorre cada producto de la venta para devolver el stock
            foreach ($sale->details as $detail) {
                $returnedQuantity = $detail->quantityProduct ?? $detail->quantity ?? 0;
                $presentation = ProductPresentation::find($detail->product_presentation_id);

                if ($presentation) {
                    $batch = $presentation->batches()
                        ->where('stock', '>=', 0)
                        ->orderBy('expiration_date', 'asc')
                        ->first();
                    if ($batch) {
                        $batch->stock += $returnedQuantity;
                        $batch->save();
                    } else {
                        // Si no hay batch, crear uno nuevo
                        Batch::create([
                            'product_presentation_id' => $detail->product_presentation_id,
                            'stock' => $returnedQuantity,
                            'expiration_date' => now()->addYear(),
                        ]);
                    }
                }

                // Hacer hard delete del detalle de venta para evitar conflicto de clave foránea
                $detail->delete();
            }

            /*
            Se marca la venta como "eliminada" (Soft Delete).
            La venta desaparecerá de la lista de 'consulta' pero se
            mantendrá en la base de datos para historial
            */
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Venta devuelta y stock restaurado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al devolver venta: " . $e->getMessage());
            return back()->withErrors(['error' => 'Error al procesar la devolución: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
