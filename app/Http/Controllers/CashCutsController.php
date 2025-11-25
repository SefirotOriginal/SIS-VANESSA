<?php

namespace App\Http\Controllers;

use App\Models\CashCuts;
use App\Models\PivotCashCuts;
use App\Models\Sale;

use Illuminate\Http\Request;


class CashCutsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cashCuts = CashCuts::with('user')->get();
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

        // Obtener último corte de caja del mismo usuario (ajusta si quieres global)
        $lastCashCut = CashCuts::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();


        // Determinar la hora de inicio y el monto inicial basado en si existe un corte previo.
        $startTime = session('login_time', now()); // Por defecto, la hora de inicio de sesión.
        $initialAmount = 0;

        if ($lastCashCut && isset($lastCashCut->final_amount)) {
            // Si el monto final del último corte fue 0, significa que no hubo ventas y el fondo se mantiene.
            // Por lo tanto, el nuevo monto inicial debe ser el monto inicial del corte anterior.
            if ((float) $lastCashCut->final_amount === 0.0) {
                $initialAmount = (float) $lastCashCut->initial_amount;
            } else {
                $initialAmount = (float) $lastCashCut->final_amount;
            }
            // Y la hora de inicio para el nuevo cálculo es la hora en que terminó el corte anterior.
            $startTime = $lastCashCut->end_time;
        }

        $endTime = now();

        // Calcular monto real de ventas entre startTime y endTime
        $realAmount = (float) Sale::where('user_id', $user->id)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->sum('amountTotal');

        // Devolver la vista con las variables ya definidas
        return view('admin.cashcuts.create', compact(
            'user',
            'startTime',
            'endTime',
            'realAmount',
            'initialAmount'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //generar corte de caja, con el dia y hora de inicio y fin, el monto inicial, monto real, monto final y la diferencia. ademas de relacionarlo con el usuario que lo creo.
        // la hora inicial se obtendra al momento de iniciar secion el usuario, y la hora final al momento de crear el corte de caja, intentar cerrar secion (salir del sistema, etc).
        // Esto taambien realacionara los datos de la tabla pivote cash_cut_has_sales_has_users

        try {
            $cashCut = CashCuts::create([
                'user_id' => auth()->user()->id,
                'start_time' => session('login_time', now()), // Usar la hora de inicio de sesion de la sesión
                'end_time' => now(),
                'initial_amount' => $request->input('initial_amount'),
                'real_amount' => $request->input('real_amount'),
                'final_amount' => $request->input('final_amount'),
                'diference' => $request->input('diference'),
            ]);
            // dd($cashCut);

            // Relacionar ventas con el corte de caja en la tabla pivote
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
                // Eliminar relaciones existentes
                PivotCashCuts::where('cash_cut_id', $cashcut->id)->delete();

                // Crear nuevas relaciones
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
