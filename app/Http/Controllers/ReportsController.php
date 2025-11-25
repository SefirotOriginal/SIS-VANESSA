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
    public function __construct()
    {
        $this->middleware('permission:reports.Sales')->only('Sales');
        $this->middleware('permission:reports.generatePredictiveReportWithGemini')->only('generatePredictiveReportWithGemini');
    }
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
        $salesList = Sale::with('details')->get();
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
            $prompt = $this->createPredictionPrompt(
                $historicalData,
                $startDate,
                $endDate,
                json_encode($salesData),            // ventas reales
                json_encode($predictions ?? []),    // predicciones
                json_encode($salesList),            // detalle de ventas
                array_sum($salesData),              // total ventas
                count($salesList),                  // num operaciones
                count($salesList) > 0 ? array_sum($salesData) / count($salesList) : 0 // ticket
            );

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
                ],
                'report_text' => $geminiResponse
            ]);
        } catch (\Exception $e) {
            Log::error('Error generando reporte predictivo: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte predictivo'], 500);
        }
    }

    /**
     * Crea el prompt para Gemini basado en datos históricos
     */
    private function createPredictionPrompt(
        $historicalData,
        $startDate,
        $endDate,
        $sales_json,
        $predictions_json,
        $sales_list_json,
        $total_ventas,
        $num_operaciones,
        $ticket_promedio
    ) {

        $dataString = "";
        foreach ($historicalData as $data) {
            $dataString .= "Fecha: {$data['date']}, Ventas Totales: {$data['total_sales']}, Número de Ventas: {$data['num_sales']}\n";
        }

        return "Genera un reporte profesional de ventas para una farmacia, usando la información proporcionada.
                === CONTEXTO DEL NEGOCIO ===
                La empresa es una farmacia. El reporte será utilizado por administradores para evaluar desempeño, comportamiento de ventas, tendencias, productos de mayor movimiento y posibles riesgos de inventario.

                === DATOS QUE TE PROPORCIONO ===
                Periodo analizado: $startDate a $endDate

                Ventas reales por día (JSON):
                $sales_json

                Predicciones generadas por el modelo (JSON):
                $predictions_json

                Detalle de ventas realizadas (productos, cantidades, precios y fechas):
                $sales_list_json

                KPIs:
                - Total de ventas: $total_ventas
                - Número de operaciones: $num_operaciones
                - Ticket promedio: $ticket_promedio

                === OBJETIVO DEL REPORTE ===
                Debes generar un análisis completo que incluya OBLIGATORIAMENTE todas las siguientes secciones, sin omitir ninguna:

                1. **TÍTULO**
                “REPORTE DE COMPORTAMIENTO DE VENTAS – FARMACIA VANESSA”

                2. **RESUMEN EJECUTIVO**
                Breve resumen general del periodo analizado.

                3. **RESUMEN DEL COMPORTAMIENTO DE VENTAS (OBLIGATORIO)**
                - Explicar cómo se comportaron las ventas en el periodo.
                - Identificar aumentos, disminuciones, estabilidad y picos.
                - Señalar días atípicos y patrones visibles.

                4. **ANÁLISIS DETALLADO DEL PERIODO**
                - Días con mayor venta.
                - Días con menor venta.
                - Posibles causas (solo con base en los datos).

                5. **TENDENCIAS**
                - Tendencias crecientes o decrecientes.
                - Patrones por día de semana.
                - Indicios de comportamiento estacional.

                6. **ANÁLISIS DE PREDICCIONES**
                - Comparar predicciones con ventas reales.
                - Indicar si se espera aumento, estabilidad o caída.
                - Riesgos u oportunidades detectadas.

                7. **ANÁLISIS DE PRODUCTOS**
                - Productos más vendidos.
                - Productos de mayor rotación.
                - Productos con baja venta.
                - Productos en riesgo de agotarse (solo si los datos lo indican).

                8. **CONCLUSIONES Y RECOMENDACIONES (OBLIGATORIO)**
                Deben incluir:
                - Recomendaciones de compra.
                - Productos que requieren reforzar inventario.
                - Acciones sugeridas basadas en el comportamiento observado.
                - Riesgos potenciales detectados en ventas o predicciones.

                === FORMATO DE RESPUESTA ===
                - Usa subtítulos claros y visibles para cada sección.
                - NO uses formato JSON en la salida final.
                - NO inventes datos ni productos que no aparecen en los datos proporcionados.
                - La respuesta debe ser texto claro, para incrustarse en un PDF.

                IMPORTANTE:
                - No utilices sintaxis de plantilla como {{variable}}, {% for %}, ni nada similar.
                - No utilices filtros como |currency, |sum.
                - Entrega el texto final listo para ponerse en un PDF.

                Redacta de manera ejecutiva y profesional.

                Genera ahora el reporte completo cumpliendo todas las secciones obligatorias.
                ";
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
