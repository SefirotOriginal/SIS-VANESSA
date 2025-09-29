<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Laboratory;
use App\Models\Presentation;
use App\Models\ProductPresentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $productPresentations = \App\Models\ProductPresentation::with(
            'product.category',
            'product.laboratory',
            'presentation',
            'batches'
        )->get();
        
        return view('product.index', compact('productPresentations'));

        /*
        $products = Product::with('category', 'laboratory', 'productPresentations.batches', 'productPresentations.presentation')->get();
        return view('product.index', compact('products'));
        */
    }

    public function create()
    {
        //Lógica para llamar al wizard de Livewire
        return view('product.create');
    }


    //Muestra el formulario para editar un producto y su primera presentación/lote.
    public function edit(Product $product)
    {
        // Cargamos el producto con su primera presentación y el primer lote de esa presentación
        $presentation = $product->productPresentations()->first();
        $batch = $presentation ? $presentation->batches()->first() : null;

        // Pasamos los datos para los menús desplegables
        $categories = Category::all();
        $laboratories = Laboratory::all();
        $presentations = Presentation::all();

        return view('product.edit', compact('product', 'presentation', 'batch', 'categories', 'laboratories', 'presentations'));
    }

    //Actualiza el producto, su presentación y su lote.
    public function update(Request $request, Product $product)
    {
        // Validación de todos los campos
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'presentation_id' => 'required|exists:presentations,id',
            'bar_code' => 'required|string|unique:product_presentations,bar_code,' . $request->presentation_to_edit_id,
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|gte:purchase_price',
            'batch_number' => 'required|string|max:255',
            'creation_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:creation_date',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gte:min_stock',
        ]);

        try {
            DB::transaction(function () use ($request, $product) {
                // Actualiza el Producto
                $product->update($request->only(['name', 'category_id', 'laboratory_id']));

                // Actualiza la Presentación
                $productPresentation = ProductPresentation::find($request->presentation_to_edit_id);
                if ($productPresentation) {
                    $productPresentation->update($request->only(['presentation_id', 'bar_code', 'purchase_price', 'sale_price']));
                }

                // Actualiza el Lote
                $batch = Batch::find($request->batch_to_edit_id);
                if ($batch) {
                    $batch->update($request->only(['batch_number', 'creation_date', 'expiration_date', 'stock', 'min_stock', 'max_stock']));
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un error al actualizar: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}