<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Producto;
use App\Models\Venta;

class VentasController extends Controller
{
    public function consultas()
    {
        $productos = Producto::all();
        $ventas = Venta::with('usuario')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('ventas.consulta', compact('ventas'));
    }

    public function crear(Request $request)
    {
        $validated = $request->validate([
            'receiptType' => 'required',
            'amountPayment' => 'required|integer',
            'amountExchange' => 'required|integer',
            'amountTotal' => 'required|integer',
        ]);
        do {
            $folio = random_int(100000, 999999); // genera un número de 6 dígitos
        } while (Venta::where('referenceNumber', $folio)->exists());
        $validated['referenceNumber'] = $folio;
        $validated['user_id'] = auth()->id();
        Venta::create($validated);
        return redirect()->route('ventas.consulta')->with('success', 'Venta creada correctamente.');
    }

    public function detalles()
    {
        return view('ventas.detalles');
    }

    public function devoluciones()
    {
        return view('ventas.devoluciones');
    }
}
