<?php

namespace App\Http\Controllers;

//use App\Models\Role;  Spatie ya lo importa
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Role::whereNull('deleted_at')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
            'guard_name' => 'required',
            'permissions' => 'nullable|array', // Es 'nullable' por si no se marca ningun rol
        ]);
        
        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'],
        ]);
        
        // 'permissions' puede no existir si no se marcó ninguno
        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }
        
        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::find($id);
        
        // Obtenemos TODOS los permisos
        $permissions = Permission::all();

        // Pasamos solo el rol y la lista de todos los permisos
        // La vista 'edit.blade.php' se encarga de ver cuáles están marcados
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            // Validamos 'permissions'
            'permissions' => 'nullable|array' 
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        // Obtenemos el array de nombres de la vista.
        // Si el usuario desmarca todos, $request->input('permissions') será null.
        // Se usa '?? []' para asegurar de que sea un array vacío en ese caso.
        $permissions = $request->input('permissions') ?? [];

        // Sincronizamos usando el array de nombres.
        $role->syncPermissions($permissions); 

        return redirect()->route('roles.index')
                        ->with('success','Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('roles.index')->with('error', 'Rol no encontrado.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('roles.index')->with('error', 'No se pudo eliminar el rol. Es posible que aún esté en uso o tenga dependencias.');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar rol: ' . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Ocurrió un error inesperado al intentar eliminar el rol.');
        }
    }
}