<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User as Usuario;

class UsuariosController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('users.create' , compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phoneNumber' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/|unique:users,phoneNumber',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);
        return redirect()->route('users.index')->with('success', 'Usuario registrado correctamente.');
    }

    public function index()
    {

        $users = Usuario::where('id', '!=', auth()->id())->get(); //Excluye al usuario activo
        $roles  = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function edit($id)
    {
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'No puedes editarte a ti mismo.');
        }
        $usuario = Usuario::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('usuario', 'roles'));
    }

    public function perfil()
    {
        $usuario = auth()->user();
        $roles = Role::all();
        return view('users.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phoneNumber' => 'nullable|string|regex:/^\+?[0-9]{7,15}$/|unique:users,phoneNumber,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->phoneNumber = $request->phoneNumber;
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }
        $usuario->save();

        $usuario->syncRoles($request->role);
        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminar($id)
    {
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
