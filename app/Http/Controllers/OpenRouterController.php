<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenRouterController extends Controller
{
    public function form()
    {
        return view('ask');
    }

    public function ask(Request $request)
    {
        $question = $request->input('question', 'What is the meaning of life?');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'shisa-ai/shisa-v2-llama3.3-70b:free',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $question,
                ],
            ]
        ]);

        $result = $response->json();

        $answer = $result['choices'][0]['message']['content'] ?? 'Gagal mendapatkan jawaban dari OpenRouter.';

        return view('ask', [
            'question' => $question,
            'answer' => $answer,
        ]);
    }
}
