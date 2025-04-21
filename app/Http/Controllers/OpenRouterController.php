<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterController extends Controller
{
    public function form()
    {
        return view('bot');
    }

    public function ask(Request $request)
    {
        $question = $request->input('question', 'What is the meaning of life?');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer 06d9c89cb8734dd7a33056248d42b853',
            ])->post('https://api.aimlapi.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $question,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
                'stream' => false
            ]);

            $result = $response->json();
            
            // Log the response for debugging
            Log::info('AIML API Response', ['response' => $result]);

            $answer = $result['choices'][0]['message']['content'] ?? 'Gagal mendapatkan jawaban dari AI.';
        } catch (\Exception $e) {
            Log::error('AIML API Error', ['error' => $e->getMessage()]);
            $answer = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        return view('bot', [
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    // API method for chatbot
    public function askApi(Request $request)
    {
        $question = $request->input('question');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer 06d9c89cb8734dd7a33056248d42b853',
            ])->post('https://api.aimlapi.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah LibrAI, sebuah asisten chatbot untuk Dige Book, situs jual beli buku. Berikan jawaban yang singkat, padat dan ramah. Selalu jawab dalam Bahasa Indonesia.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $question,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 300,
                'stream' => false,
                'response_format' => [
                    'type' => 'text'
                ]
            ]);

            $result = $response->json();
            
            // Log the response for debugging
            Log::info('AIML API Response (Chat)', ['response' => $result]);

            $answer = $result['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';
        } catch (\Exception $e) {
            Log::error('AIML API Error (Chat)', ['error' => $e->getMessage(), 'question' => $question]);
            $answer = 'Maaf, terjadi kesalahan saat memproses permintaan Anda.';
        }

        return response()->json([
            'question' => $question,
            'answer' => $answer,
        ]);
    }
}