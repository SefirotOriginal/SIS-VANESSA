<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Laboratorio;
use App\Models\Lote;


class ProductosController extends Controller
{
    public function creacion()
    {
        $categorias = Categoria::select('id', 'name')->get();
        $laboratorios = Laboratorio::select('id', 'name')->get();
        $lotes = Lote::select('id', 'batchNumber')->get();
        return view('productos.creacion', compact('categorias', 'laboratorios', 'lotes'));
    }

    public function consultas()
    {
        $productos = Producto::with(['category', 'laboratory', 'batch'])->get(); // Obtener todos los productos desde la BD
        return view('productos.consulta', compact('productos'));  // Pasar los productos a la vista
    }

    public function edicion($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::select('id', 'name')->get();
        $laboratorios = Laboratorio::select('id', 'name')->get();
        $lotes = Lote::select('id', 'batchNumber')->get();
        return view('productos.edicion', compact('producto', 'categorias', 'laboratorios', 'lotes'));
    }

    public function crear(Request $request)
    {
        $validated = $request->validate([
            'barCode' => 'required|unique:products',
            'name' => 'required',
            'description' => 'nullable',
            'currentStock' => 'required|integer',
            'mintStock' => 'required|integer',
            'maxStock' => 'required|integer',
            'purchasePrice' => 'required|numeric',
            'salePrice' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'batch_id' => 'required|exists:batches,id'
        ]);
        Producto::create($validated);
        return redirect()->route('productos.consulta')->with('success', 'Producto creado correctamente.');
    }

    public function eliminar($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect()->route('productos.consulta')->with('success', 'Producto eliminado correctamente.');
    }

    public function actualizar(Request $request, $id)
    {
        $validated = $request->validate([
            'barCode' => 'required|unique:products,barCode,' . $id,
            'name' => 'required',
            'description' => 'nullable',
            'currentStock' => 'required|integer',
            'mintStock' => 'required|integer',
            'maxStock' => 'required|integer',
            'purchasePrice' => 'required|numeric',
            'salePrice' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'batch_id' => 'required|exists:batches,id'
        ]);
        $producto = Producto::findOrFail($id);
        $producto->update($validated);
        return redirect()->route('productos.consulta')->with('success', 'Producto actualizado correctamente.');
    }
}
