<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function creacion()
    {
        return view('usuarios.creacion');
    }

    public function consultas()
    {
        return view('usuarios.consulta');
    }

    public function edicion()
    {
        return view('usuarios.edicion');
    }
}
