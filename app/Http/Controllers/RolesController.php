<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function creacion()
    {
        return view('roles.creacion');
    }

    public function consultas()
    {
        return view('roles.consulta');
    }

    public function edicion()
    {
        return view('roles.edicion');
    }
}
