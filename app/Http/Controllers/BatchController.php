<?php

namespace App\Http\Controllers;

use App\Models\batch;
use App\Models\Producto;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = batch::all();
        return view('admin.batch.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Producto::all(); // Assuming you have a Product model
        return view('admin.batch.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|max:255',
            'creation_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:creation_date',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
        ]);

        batch::create([
            'product_id' => $request->product_id,
            'batch_number' => $request->batch_number,
            'creation_date' => $request->creation_date,
            'expiration_date' => $request->expiration_date,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock,
            'max_stock' => $request->max_stock,
        ]);

        return redirect()->route('batches.index')->with('success', 'Lote creado correctamente.');
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
