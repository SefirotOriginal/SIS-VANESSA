<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reportes.creacion')->only('creacion');
        $this->middleware('permission:reportes.consultas')->only('consultas');
        $this->middleware('permission:reportes.detalles')->only('detalles');
    }
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
