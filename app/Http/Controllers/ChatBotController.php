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
        $userMessage = $request->input('message');

        // 1. Inyectar Contexto: Traemos un resumen rápido de lo que hay en la BD
        // (Limitamos a 20 para no saturar la memoria en esta versión simple)
        $organicos = Organico::select('id', 'nombre', 'precio', 'stock')->take(20)->get();
        $maquinarias = Maquinaria::select('id', 'modelo', 'precio_alquiler')->take(20)->get();

        $contexto = "Catálogo de Orgánicos: " . json_encode($organicos) . "\n";
        $contexto .= "Catálogo de Maquinarias: " . json_encode($maquinarias) . "\n";

        // 2. El Prompt Maestro (La personalidad de la IA)
        $prompt = "Eres el asistente virtual de AgroVida Bolivia. Sé breve, amable y directo. 
        Revisa este catálogo de nuestra base de datos: \n" . $contexto . "
        Si el usuario busca algo que está en el catálogo, dile que sí lo tenemos, menciónale el precio y dile que puede buscarlo en el menú principal. Si no lo tenemos, discúlpate amablemente.
        Pregunta del usuario: " . $userMessage;

        // 3. Hablar con Gemini
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        $response = Http::post($url, [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]);

        if ($response->successful()) {
            // Extraer la respuesta del JSON que devuelve Google
            $respuestaIA = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Lo siento, no pude procesar eso.';
            return response()->json(['reply' => $respuestaIA]);
        }

        return response()->json(['reply' => 'Error de conexión con el asistente.'], 500);
    }
}