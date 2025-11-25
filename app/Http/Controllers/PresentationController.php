<?php

namespace App\Http\Controllers;

use App\Models\Presentation;

use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:presentation.index')->only('index');
        $this->middleware('permission:presentation.create|presentation.store')->only(['create', 'store']);
        $this->middleware('permission:presentation.edit|presentation.update')->only(['edit', 'update']);
        $this->middleware('permission:presentation.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $ProductPresentations = ProductPresentation::with('product', 'presentation')->get();
        $presentations = Presentation::all();
        return view('admin.presentation.index', compact('presentations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.presentation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $presentation = Presentation::create($validated);
        return redirect()->route('presentations.edit', $presentation)->with('success', 'Presentación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Presentation $presentation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presentation $presentation)
    {
        // Mostrar formulario de edición para la presentación proporcionada (binding implícito)
        return view('admin.presentation.edit', compact('presentation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presentation $presentation)
    {
        // Validar y actualizar la presentación
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $presentation->update($validated);
        return redirect()->route('presentations.index', $presentation)->with('success', 'Presentación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presentation $presentation)
    {
        //
        $presentation->delete();
        return redirect()->route('presentations.index')->with('success', 'Presentación eliminada exitosamente.');
    }
}
