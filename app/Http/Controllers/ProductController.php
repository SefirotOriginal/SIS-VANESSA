<?php

namespace App\Http\Controllers;

use App\Models\batch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::with([
            'category',
            'laboratory',
            'productPresentations' => function ($query) {
                $query->with([
                    'presentation',
                    'batches' => function ($batchQuery) {
                        $batchQuery->select(
                            'id',
                            'product_presentation_id',
                            'batch_number',
                            'creation_date',
                            'expiration_date',
                            'stock',
                            'min_stock',
                            'max_stock'
                        );
                    }
                ]);
            }
        ])->get();
        $products = Product::with(['category', 'laboratory'])->get();
        return view('product.index', compact('products')); // Assuming you have a view for listing products
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $batches = batch::all(); // Assuming you have a Product model
        $categories = Category::all(); // Assuming you have a Category model
        $laboratories = Laboratory::all(); // Assuming you have a Laboratory model
        return view('product.create', compact('categories', 'laboratories', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'description' => 'nullable|string|max:1000',
            'usage' => 'required|string|max:500',
            'status' => 'boolean',
        ]);
        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
