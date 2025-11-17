<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductPresentation;
// use App\Models\Producto; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $productos = ProductPresentation::with('product')
                                ->whereHas('product')
                                ->withSum('batches', 'stock') 
                                ->whereHas('batches', function ($query) {
                                    $query->where('stock', '>', 0);
                                })
                                ->get();
        return view('home', compact('productos'));
    }
}