<?php

namespace App\Http\Controllers;

// use Gemini; // Usar el Facade global
use App\Models\Sale;
use App\Models\User;
use App\Models\DetailSale;
use App\Models\Product;

use App\Models\Reports;
// use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // redirecciona a la vista de reporte de ventas
    public function Sales(Request $request)
    {
        $sales = Sale::all();
        $salesCount = $sales->count();
        $salesDetails = $sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'user_id' => $sale->user_id,
                'product_id' => $sale->product_id,
                'amountTotal' => $sale->amountTotal,
                'created_at' => $sale->created_at,
            ];
        });

        return view('admin.reports.sale', compact('sales', 'salesCount', 'salesDetails'));
    }

    /**
     * Genera un reporte de análisis y predicción de ventas usando Gemini Pro.
     */
    public function generatePredictiveReportWithGemini(Request $request, \App\Services\GeminiService $gemini)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Obtener ventas reales en el rango de fechas
            $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get()
                ->groupBy(function ($sale) {
                    return $sale->created_at->format('Y-m-d');
                });

            // Preparar datos para gráficos
            $salesLabels = [];
            $salesData = [];

            foreach ($sales as $date => $daySales) {
                $salesLabels[] = $date;
                $salesData[] = $daySales->sum('amountTotal');
            }

            // Preparar datos históricos para Gemini
            $historicalData = [];
            foreach ($sales as $date => $daySales) {
                $historicalData[] = [
                    'date' => $date,
                    'total_sales' => $daySales->sum('amountTotal'),
                    'num_sales' => $daySales->count()
                ];
            }

            // Crear prompt para Gemini
            $prompt = $this->createPredictionPrompt($historicalData, $startDate, $endDate);

            // Llamar a Gemini API
            $geminiResponse = $gemini->generarContenido($prompt);

            // Procesar respuesta de Gemini y extraer predicciones
            $predictions = $this->parseGeminiPredictions($geminiResponse, $endDate);

            // Obtener lista detallada de ventas para la tabla
            $salesList = Sale::whereBetween('created_at', [$startDate, $endDate])
                ->with('user', 'details.product')
                ->orderBy('created_at')
                ->get()
                ->map(function ($sale) {
                    return [
                        'id' => $sale->id,
                        'referenceNumber' => $sale->referenceNumber,
                        'user' => $sale->user->name ?? 'N/A',
                        'amountTotal' => $sale->amountTotal,
                        'created_at' => $sale->created_at->format('Y-m-d H:i:s'),
                        'details' => $sale->details->map(function ($detail) {
                            return [
                                'product' => $detail->product->name ?? 'N/A',
                                'quantity' => $detail->quantity,
                                'price' => $detail->price,
                                'subtotal' => $detail->subtotal
                            ];
                        })
                    ];
                });

            return response()->json([
                'sales' => [
                    'labels' => $salesLabels,
                    'data' => $salesData,
                    'list' => $salesList
                ],
                'predictions' => [
                    'labels' => $predictions['labels'],
                    'data' => $predictions['data']
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error generando reporte predictivo: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte predictivo'], 500);
        }
    }

    /**
     * Crea el prompt para Gemini basado en datos históricos
     */
    private function createPredictionPrompt($historicalData, $startDate, $endDate)
    {
        $dataString = "";
        foreach ($historicalData as $data) {
            $dataString .= "Fecha: {$data['date']}, Ventas Totales: {$data['total_sales']}, Número de Ventas: {$data['num_sales']}\n";
        }

        return "Analiza los siguientes datos históricos de ventas y proporciona predicciones para los próximos 30 días:

Datos históricos:
{$dataString}

Por favor, analiza las tendencias, patrones estacionales y crecimiento. Proporciona predicciones diarias de ventas totales para los próximos 30 días a partir de {$endDate}.

Responde únicamente con un JSON válido en el siguiente formato:
{
  \"predictions\": [
    {\"date\": \"YYYY-MM-DD\", \"predicted_sales\": 123.45},
    {\"date\": \"YYYY-MM-DD\", \"predicted_sales\": 234.56},
    ...
  ]
}";
    }

    /**
     * Parsea las predicciones de la respuesta de Gemini
     */
    private function parseGeminiPredictions($geminiResponse, $endDate)
    {
        try {
            // Intentar extraer JSON de la respuesta
            $jsonStart = strpos($geminiResponse, '{');
            $jsonEnd = strrpos($geminiResponse, '}') + 1;
            $jsonString = substr($geminiResponse, $jsonStart, $jsonEnd - $jsonStart);

            $parsed = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($parsed['predictions'])) {
                // Si no se puede parsear, generar predicciones simples basadas en promedio
                return $this->generateSimplePredictions($endDate);
            }

            $labels = [];
            $data = [];

            foreach ($parsed['predictions'] as $prediction) {
                $labels[] = $prediction['date'];
                $data[] = (float) $prediction['predicted_sales'];
            }

            return ['labels' => $labels, 'data' => $data];
        } catch (\Exception $e) {
            Log::warning('Error parseando respuesta de Gemini: ' . $e->getMessage());
            return $this->generateSimplePredictions($endDate);
        }
    }

    /**
     * Genera predicciones simples basadas en promedio histórico si Gemini falla
     */
    private function generateSimplePredictions($endDate)
    {
        $labels = [];
        $data = [];
        $baseDate = new \DateTime($endDate);

        // Calcular promedio de ventas de los últimos 30 días
        $recentSales = Sale::where('created_at', '>=', now()->subDays(30))
            ->sum('amountTotal');
        $avgDailySales = $recentSales / 30;

        for ($i = 1; $i <= 30; $i++) {
            $date = clone $baseDate;
            $date->modify("+{$i} days");
            $labels[] = $date->format('Y-m-d');
            // Añadir variación aleatoria del ±20%
            $variation = $avgDailySales * (0.8 + (mt_rand(0, 40) / 100));
            $data[] = round($variation, 2);
        }

        return ['labels' => $labels, 'data' => $data];
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
    public function show(Reports $reports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reports $reports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reports $reports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reports $reports)
    {
        //
    }
}
