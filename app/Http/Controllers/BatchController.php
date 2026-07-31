<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\ProductPresentation;
use Illuminate\Http\Request;
use PHPUnit\Event\Test\PreConditionCalledSubscriber;

class BatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:batch.index')->only('index');
        $this->middleware('permission:batch.create|batch.store')->only(['create', 'store']);
        $this->middleware('permission:batch.edit|batch.update')->only(['edit', 'update']);
        $this->middleware('permission:batch.destroy')->only('destroy');
    }
    public function index()
    {
        $batches = Batch::with('productPresentation.product')->latest()->get();
        return view('admin.batch.index', compact('batches'));
    }

    public function create()
    {
        // $products_presentation = ProductPresentation::with('product', 'presentation')->get();
        $products_presentation = ProductPresentation::with([
            'product' => function ($query) {
                $query->withTrashed();
            }])->whereHas('product', function ($query) {
                $query->whereNull('deleted_at');
            })->get();
        return view('admin.batch.create', compact('products_presentation'));
    }

    public function store(Request $request)
    {
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

    public function show(Batch $batch)
    {
        // No se suele usar en este tipo de CRUD, pero lo dejamos por si acaso
    }

    //Muestra el formulario para editar un lote.
    public function edit(Batch $batch)
    {
        $products_presentation = ProductPresentation::with('product', 'presentation')->get();
        return view('admin.batch.edit', compact('batch', 'products_presentation'));
    }

    //Actualiza el lote en la base de datos.
    public function update(Request $request, Batch $batch)
    {
        //Usamos las mismas reglas de validación que en 'store'
        $validate = $request->validate([
            'batch_number' => 'required|string|max:255',
            'product_presentation_id' => 'required|exists:product_presentations,id',
            'creation_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:creation_date',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gte:min_stock',
        ]);

        $batch->update($validate);

        return redirect()->route('batches.index')->with('success', 'Lote actualizado exitosamente.');
    }

    //Elimina el lote de la base de datos.
    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Lote eliminado exitosamente.');
    }
}
