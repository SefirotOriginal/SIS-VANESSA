<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User as Usuario;
use Illuminate\Support\Facades\Storage;

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
            'imagenPerfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        $path = null;
        if ($request->hasFile('imagenPerfil')) {
            // Guarda la imagen en 'storage/app/public/profile_pictures'
            $path = $request->file('imagenPerfil')->store('profile_pictures', 'public');
        }

        $user = Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'password' => bcrypt($request->password),
            'profile_photo_path' => $path
        ]);

        $user->assignRole($request->role);
        return redirect()->route('users.index')->with('success', 'Usuario registrado correctamente.');
    }

    public function index()
    {
        // $users = Usuario::where('id', '!=', auth()->id())->get(); //Excluye al usuario activo
        $users = Usuario::all();
        $roles  = Role::all();
        // dd($users, $roles);
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
            'imagenPerfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->phoneNumber = $request->phoneNumber;
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        if ($request->hasFile('imagenPerfil')) {
            // Borra la foto anterior
            if ($usuario->profile_photo_path) {
                Storage::disk('public')->delete($usuario->profile_photo_path);
            }
            // Guarda la nueva foto y actualiza el path
            $path = $request->file('imagenPerfil')->store('profile_pictures', 'public');
            $usuario->profile_photo_path = $path;
        }

        $usuario->save();

        $usuario->syncRoles($request->role);
        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }


    public function destroy($id)
    {
        $user = Usuario::findOrFail($id); 

        // Lógica para prevenir auto-eliminación
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}