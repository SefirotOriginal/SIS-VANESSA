<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuariosController extends Controller
{
    public function creacion()
    {
        
        return view('usuarios.creacion');
    }

    public function crear(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'email_verified_at' => 'nullable',
            'password' => 'required',
            'remember_token' => 'nullable'
        ]);
        Usuario::create($validated);
        return redirect()->route('usuarios.consulta')->with('success', 'Usuario registrado correctamente.');
    }

    public function consultas()
    {
        $usuarios = Usuario::where('id', '!=', auth()->id())->get(); //Excluye al usuario activo
        return view('usuarios.consulta', compact('usuarios'));
    }

    public function edicion($id)
    {
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'No puedes editarte a ti mismo.');
        }
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edicion', compact('usuario'));
    }

    public function perfil()
    {
        $usuario = auth()->user();
        return view('usuarios.edicion', compact('usuario'));
    }

    public function actualizar(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'email_verified_at' => 'nullable|date',
            'password' => 'required',
            'remember_token' => 'nullable'
        ]);
        $usuario = Usuario::findOrFail($id);
        $usuario->update($validated);
        return redirect()->route('usuarios.consulta')->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminar($id)
    {
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return redirect()->route('usuarios.consulta')->with('success', 'Usuario eliminado correctamente.');
    }
}
