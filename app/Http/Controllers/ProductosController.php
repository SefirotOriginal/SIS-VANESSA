<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function creacion()
    {
        return view('productos.creacion');
    }

    public function consultas()
    {
        return view('productos.consulta');
    }

    public function edicion()
    {
        return view('productos.edicion');
    }
}
