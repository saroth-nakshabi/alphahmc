<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatWidgetController extends Controller
{
    public function reply(Request $request)
    {
        $request->validate([
            'message'      => 'required|string|max:500',
            'history'      => 'nullable|array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:1000',
            'service_name' => 'nullable|string|max:100',
        ]);

        // Build the system prompt — scoped to your business context
        $service     = $request->input('service_name', 'our services');
        $systemPrompt = <<<EOT
You are a helpful support assistant for a healthcare/services company.
The user is currently viewing information about: {$service}.

Your job:
- Answer questions about {$service} clearly and helpfully in 1-3 short sentences.
- If the question needs a doctor, specialist, or booking, say so and recommend they chat with the team on WhatsApp.
- Never give specific medical diagnoses or prescribe medication.
- Keep replies conversational, warm, and concise — this is a chat widget, not an essay.
- If you cannot answer confidently, say "I'm not sure about that — our team on WhatsApp can help you directly."
- Always reply in the same language the user wrote in.
EOT;

        // Build messages array: history + new message
        $history = collect($request->input('history', []))
            ->map(fn($m) => ['role' => $m['role'], 'content' => $m['content']])
            ->values()
            ->toArray();

        $messages = array_merge($history, [
            ['role' => 'user', 'content' => $request->input('message')]
        ]);

        try {
            $response = Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->timeout(15)->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-haiku-4-5-20251001',  // Fast + cheap for chat widget
                'max_tokens' => 300,
                'system'     => $systemPrompt,
                'messages'   => $messages,
            ]);

            if ($response->failed()) {
                Log::warning('Claude API error', ['status' => $response->status()]);
                return response()->json(['reply' => null, 'error' => 'upstream'], 502);
            }

            $reply = $response->json('content.0.text', null);

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            Log::error('ChatWidget exception', ['msg' => $e->getMessage()]);
            return response()->json(['reply' => null, 'error' => 'exception'], 500);
        }
    }
}