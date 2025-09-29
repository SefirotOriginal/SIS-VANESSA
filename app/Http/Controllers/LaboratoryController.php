<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    /**
     * Muestra la lista de laboratorios.
     */
    public function index()
    {
        $laboratories = Laboratory::all();
        // Asegúrate de que la ruta de tu vista sea correcta (ej: 'admin.laboratory.index')
        return view('admin.laboratory.index', compact('laboratories'));
    }

    /**
     * Muestra el formulario para crear un nuevo laboratorio.
     */
    public function create()
    {
        $states = [
            'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
            'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
            'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 
            'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 
            'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
        ];
        return view('admin.laboratory.create', compact('states'));
    }

    /**
     * Guarda un nuevo laboratorio.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:laboratories,name',
            'state' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        Laboratory::create($request->all());
        return redirect()->route('laboratories.index')->with('success', 'Laboratorio creado exitosamente.');    
    }

    /**
     * Muestra el formulario para editar un laboratorio.
     */
    public function edit(Laboratory $laboratory)
    {
        $states = [
            'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
            'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
            'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 
            'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 
            'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
        ];

        return view('admin.laboratory.edit', compact('laboratory', 'states'));
    }

    /**
     * Actualiza un laboratorio existente.
     */
    public function update(Request $request, Laboratory $laboratory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:laboratories,name,' . $laboratory->id,
            'state' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        $laboratory->update($request->all());
        return redirect()->route('laboratories.index')->with('success', 'Laboratorio actualizado exitosamente.');
    }

    /**
     * Elimina un laboratorio.
     */
    public function destroy(Laboratory $laboratory)
    {
        // Añadir lógica de verificación si es necesario (ej: si el laboratorio tiene productos asociados)
        $laboratory->delete();
        return redirect()->route('laboratories.index')->with('success', 'Laboratorio eliminado exitosamente.');
    }
}