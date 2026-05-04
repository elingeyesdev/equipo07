<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Organico;
use App\Models\Maquinaria;

class ChatBotController extends Controller
{ 

public function ask(Request $request)
    {
        try {
            $userMessage = $request->input('message');

            $organicos = Organico::select('id', 'nombre')->take(20)->get();
            $maquinarias = Maquinaria::select('id', 'modelo')->take(20)->get();

            $contexto = "Catálogo de Orgánicos: " . json_encode($organicos) . "\n";
            $contexto .= "Catálogo de Maquinarias: " . json_encode($maquinarias) . "\n";

            $prompt = "Eres el asistente de AgroVida. Revisa este catálogo: \n" . $contexto . "\nPregunta: " . $userMessage;

            $apiKey = env('GEMINI_API_KEY');
            if (empty($apiKey)) {
                return response()->json(['reply' => '⚠️ ERROR: Falta la GEMINI_API_KEY en .env'], 500);
            }

            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

            $response = Http::post($url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            // VALIDACIÓN MEJORADA: Clasificación de errores reales
            if (!$response->successful()) {
                $codigoHTTP = $response->status();
                $mensajeGoogle = $response->json()['error']['message'] ?? 'Error desconocido de la API';

                // Si realmente es el error 404 (Modelo no encontrado)
                if ($codigoHTTP == 404 && strpos($mensajeGoogle, 'is not found') !== false) {
                    $listUrl = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;
                    $listResp = Http::get($listUrl);
                    
                    $modelosDisponibles = "No se pudo obtener la lista.";
                    if ($listResp->successful()) {
                        $nombres = [];
                        foreach ($listResp->json()['models'] ?? [] as $mod) {
                            $nombres[] = str_replace('models/', '', $mod['name']);
                        }
                        $modelosDisponibles = implode(', ', $nombres);
                    }
                    return response()->json(['reply' => "⚠️ MODELO RECHAZADO. Usa uno de estos:\n" . $modelosDisponibles], 500);
                }

                // Si es un error de Rate Limit (Demasiadas peticiones)
                if ($codigoHTTP == 429) {
                    return response()->json(['reply' => "⏳ Espera un momento, estás enviando mensajes muy rápido."], 500);
                }

                // Cualquier otro error (ej. Seguridad, Servicio Caído)
                return response()->json(['reply' => "⚠️ AVISO DE GOOGLE [Error $codigoHTTP]: " . $mensajeGoogle], 500);
            }

            $respuestaIA = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Respuesta vacía.';
            return response()->json(['reply' => $respuestaIA]);

        } catch (\Throwable $e) {
            return response()->json([
                'reply' => '🛑 ERROR INTERNO: ' . $e->getMessage() . ' (Línea ' . $e->getLine() . ')'
            ], 500);
        }
    }

} 