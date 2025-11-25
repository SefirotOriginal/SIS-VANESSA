<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:category.index')->only('index');
        $this->middleware('permission:category.create|category.store')->only(['create', 'store']);
        $this->middleware('permission:category.edit|category.update')->only(['edit', 'update']);
        $this->middleware('permission:category.destroy')->only('destroy');
    }
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', '¡Categoría creada con éxito!');
    }

    public function show(Category $category)
    {
        // Generalmente no se usa en un CRUD con tabla, pero lo dejamos por si acaso
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', '¡Categoría actualizada con éxito!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', '¡Categoría eliminada con éxito!');
    }
}
