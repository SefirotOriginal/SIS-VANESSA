<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Provider;
use App\Models\ProductPresentation;
use App\Models\Batch;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with([
            'provider',
            'user',
            'details.product',
            'details.productPresentation.presentation',
            'details.batch'
        ])->orderBy('created_at', 'desc')->get();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $providers = Provider::all();
        $products = ProductPresentation::with(['product', 'presentation', 'batches'])->get();
        
        return view('purchases.create', compact('providers', 'products'));
    }

    // Se guarda la compra y se actualiza el inventario por lote
    public function store(Request $request)
    {
        $request->validate([
            'provider_id'      => 'required|exists:providers,id',
            'reference_number' => 'required|unique:purchases,reference_number',
            'receipt_type'     => 'required',
            'items'            => 'required|array|min:1', // Debe haber al menos 1 producto

            'items.*.product_presentation_id' => 'required|exists:product_presentations,id',
            'items.*.quantity'                => 'required|integer|min:1',
            'items.*.purchase_price'          => 'required|numeric|min:0',
            'items.*.sale_price'              => 'nullable|numeric|min:0', // Opcional actualizar precio venta
            'items.*.batch_number'            => 'required|string',
            'items.*.expiration_date'         => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $totalCompra = 0;
            foreach($request->items as $item){
                $totalCompra += ($item['quantity'] * $item['purchase_price']);
            }

            $purchase = Purchase::create([
                'reference_number' => $request->reference_number,
                'receipt_type'     => $request->receipt_type,
                'amountTotal'      => $totalCompra,
                'user_id'          => Auth::id(),
                'provider_id'      => $request->provider_id,
            ]);

            foreach ($request->items as $item) {
                
                $prodPresentation = ProductPresentation::find($item['product_presentation_id']);

                // Se busca si ya existe el lote ingresado para este producto específico
                $batch = Batch::where('product_presentation_id', $item['product_presentation_id'])
                              ->where('batch_number', $item['batch_number'])
                              ->first();

                if ($batch) {
                    // Si el lote existe, se suma al stock
                    $batch->stock += $item['quantity'];
                    $batch->save();
                } else {
                    // En caso de no existir, se crea el nuevo lote
                    $batch = Batch::create([
                        'product_presentation_id' => $item['product_presentation_id'],
                        'batch_number'            => $item['batch_number'],
                        'creation_date'           => now(), // Fecha registro
                        'expiration_date'         => $item['expiration_date'],
                        'stock'                   => $item['quantity'],
                        'min_stock'               => 5,  // Valor por defecto
                        'max_stock'               => 100, // Valor por defecto
                    ]);
                }

                // Se actualiza el costo del producto al precio de esta compra
                $prodPresentation->purchase_price = $item['purchase_price'];
                
                // Si el usuario definió un nuevo precio de venta, se actualiza también
                if(!empty($item['sale_price'])){
                     $prodPresentation->sale_price = $item['sale_price'];
                }
                $prodPresentation->save();

                // Se crea el detalle de la compra
                PurchaseDetail::create([
                    'purchase_id'             => $purchase->id,
                    'product_presentation_id' => $item['product_presentation_id'],
                    'batch_id'                => $batch->id,
                    'product_id'              => $prodPresentation->product_id,
                    'purchase_price'          => $item['purchase_price'],
                    'sale_price'              => $prodPresentation->sale_price,
                    'stock'                   => $item['quantity'], // Cantidad comprada
                    'amount_total'            => $item['quantity'] * $item['purchase_price'],
                ]);
            }

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Compra registrada y stock actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar compra: ' . $e->getMessage())->withInput();
        }
    }

    // Elimina la compra y revierte el stock asociado
    public function destroy($id)
    {
        // Se busca la compra junto con sus detalles
        $purchase = Purchase::with('details')->findOrFail($id);

        try {
            DB::beginTransaction();

            // Se revierte el stock de cada producto en el lote correspondiente
            foreach ($purchase->details as $detail) {
                if ($detail->batch_id) {
                    $batch = Batch::find($detail->batch_id);
                    
                    if ($batch) {
                        // Se resta la cantidad que se había comprado
                        $batch->stock -= $detail->stock; 
                        
                        // Se evitan los números negativos si es que ya se vendió mercancía
                        if ($batch->stock < 0) {
                            $batch->stock = 0; 
                        }
                        
                        $batch->save();
                    }
                }
            }

            $purchase->delete();

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Compra eliminada y stock revertido correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchases.index')->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}