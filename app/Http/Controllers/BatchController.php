<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Producto;
use App\Models\ProductPresentation;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = Batch::with('productPresentation.product')->latest()->get();
        return view('admin.batch.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products_presentation = ProductPresentation::with('product', 'presentation')->get(); // Assuming you have a Product model
        return view('admin.batch.create', compact('products_presentation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'batch_number' => 'required|string|max:255',
            'product_presentation_id' => 'required|exists:product_presentations,id',
            'creation_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:creation_date',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gte:min_stock',
        ]);

        Batch::create($validate);

        return redirect()->route('batches.index')->with('success', 'Lote creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(batch $batch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, batch $batch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(batch $batch)
    {
        //
    }
}
