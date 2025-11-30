<?php

namespace App\Http\Controllers;

use App\Models\CashCuts;
use App\Models\PivotCashCuts;
use App\Models\Sale;
use App\Models\Purchase;

use Illuminate\Http\Request;


class CashCutsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cashcuts.index')->only('index');
        $this->middleware('permission:cashcuts.create|cashcuts.store')->only(['create', 'store']);
        $this->middleware('permission:cashcuts.edit|cashcuts.update')->only(['edit', 'update']);
        $this->middleware('permission:cashcuts.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Esto precarga todas las ventas y compras asociadas a cada corte
        $cashCuts = CashCuts::with(['user', 'sales', 'purchases'])->get();
        $sales = Sale::with('user')->get(); 
        
        return view('admin.cashcuts.index', compact('cashCuts', 'sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        // Se obtiene el último corte de caja
        $lastCashCut = CashCuts::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Se determina hora de inicio y monto inicial
        $startTime = session('login_time', now()); 
        $initialAmount = 0;

        if ($lastCashCut && isset($lastCashCut->final_amount)) {
            if ((float) $lastCashCut->final_amount === 0.0) {
                $initialAmount = (float) $lastCashCut->initial_amount;
            } else {
                $initialAmount = (float) $lastCashCut->final_amount;
            }
            $startTime = $lastCashCut->end_time;
        }

        $endTime = now();

        // Calcular Ventas
        $salesTotal = (float) Sale::where('user_id', $user->id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->sum('amountTotal');

        // Calcular Compras
        $purchasesTotal = (float) Purchase::where('user_id', $user->id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->sum('amountTotal');

        // Calcular Monto esperado (Inicial + Ventas - Compras)
        $realAmount = $initialAmount + $salesTotal - $purchasesTotal;

        return view('admin.cashcuts.create', compact(
            'user',
            'startTime',
            'endTime',
            'realAmount',
            'initialAmount',
            'salesTotal',
            'purchasesTotal'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $cashCut = CashCuts::create([
                'user_id' => auth()->user()->id,
                'start_time' => session('login_time', now()), 
                'end_time' => now(), // Este 'now()' define el cierre exacto
                'initial_amount' => $request->input('initial_amount'),
                'real_amount' => $request->input('real_amount'),
                'final_amount' => $request->input('final_amount'),
                'diference' => $request->input('diference'),
            ]);

            $sales = Sale::where('user_id', auth()->user()->id)
                ->whereBetween('created_at', [$cashCut->start_time, $cashCut->end_time])
                ->get();

            foreach ($sales as $sale) {
                PivotCashCuts::create([
                    'cash_cut_id' => $cashCut->id,
                    'sale_id' => $sale->id,
                    'user_id' => auth()->user()->id,
                ]);
            }

            // Se buscan las compras hechas en este rango de tiempo
            $purchases = Purchase::where('user_id', auth()->user()->id)
                ->whereBetween('created_at', [$cashCut->start_time, $cashCut->end_time])
                ->get();
            
            if ($purchases->count() > 0) {
                $cashCut->purchases()->attach($purchases);
            }

            return redirect()->route('cashcuts.index')->with('success', 'Corte de caja creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el corte de caja: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CashCuts $cashcut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashCuts $cashcut)
    {
        return view('admin.cashcuts.edit', compact('cashcut'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashCuts $cashcut)
    {
        //
        if (!$cashcut) {
            return redirect()->back()->with('error', 'Corte de caja no encontrado.');
        }
        try {
            $cashcut->update([
                'end_time' => now(),
                'initial_amount' => $request->input('initial_amount'),
                'real_amount' => $request->input('real_amount'),
                'final_amount' => $request->input('final_amount'),
                'diference' => $request->input('diference'),
            ]);

            if ($request->has('sales')) {
                // Se eliminan las relaciones existentes
                PivotCashCuts::where('cash_cut_id', $cashcut->id)->delete();

                // Se crean nuevas relaciones
                foreach ($request->input('sales') as $saleId) {
                    PivotCashCuts::updateOrCreate([
                        'cash_cut_id' => $cashcut->id,
                        'sale_id' => $saleId,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }

            return redirect()->route('cashcuts.index')->with('success', 'Corte de caja actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el corte de caja: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashCuts $cashcut)
    {
        //
        $cashcut->delete();
        return redirect()->route('cashcuts.index')->with('success', 'Corte de caja eliminado exitosamente.');
    }
}
