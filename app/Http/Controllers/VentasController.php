<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function consultas()
    {
        return view('ventas.consulta');
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
