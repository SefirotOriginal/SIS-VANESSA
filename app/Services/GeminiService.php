<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models";

    public function generarContenido($prompt, $model = "gemini-2.5-flash")
    {
        $url = "{$this->baseUrl}/{$model}:generateContent?key=" . config('services.gemini.key');

        $response = Http::timeout(120)->post($url, [
            "contents" => [
                ["parts" => [["text" => $prompt]]]
            ]
        ]);

        if ($response->failed()) {
            throw new \Exception("Error en Gemini: " . $response->body());
        }

        return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }

    // Reporte de ventas
    public function generarReporteVentas($ventas)
    {
        $prompt = "
            Genera un reporte detallado de ventas basado en:
            " . json_encode($ventas) . "

            Incluye:
            - Resumen general
            - Productos más vendidos
            - Tendencias
            - Conclusiones
            - Recomendaciones
        ";

        return $this->generarContenido($prompt);
    }

    // Predicción de ventas
    public function predecirVentas($dataset)
    {
        $prompt = "
            Estos son datos históricos de ventas (fecha y total):
            " . json_encode($dataset) . "

            Realiza:
            1. Análisis de tendencia
            2. Predicción para los próximos 15 días
            3. Explica de forma clara tus conclusiones
        ";

        return $this->generarContenido($prompt);
    }
}
