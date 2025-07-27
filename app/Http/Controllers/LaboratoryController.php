<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $laboratories = Laboratory::all();
        return view('admin.laboratory.index', compact('laboratories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LaboratoryController $laboratoryController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaboratoryController $laboratoryController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaboratoryController $laboratoryController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaboratoryController $laboratoryController)
    {
        //
    }
}
