<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function creacion()
    {
        return view('reportes.creacion');
    }

    public function consultas()
    {
        return view('reportes.consulta');
    }

    public function detalles()
    {
        return view('reportes.detalles');
    }
}
