<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CortesController extends Controller
{
    public function creacion()
    {
        return view('cortes.creacion');
    }

    public function consultas()
    {
        return view('cortes.consulta');
    }

    public function detalles()
    {
        return view('cortes.detalles');
    }
}
