<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\MainCategory;
use Illuminate\Support\Str;

class ChatAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $lowerMessage = strtolower($message);

        // Simple Rule-based AI logic (Can be replaced with OpenAI/Gemini API call)
        $response = $this->getAiResponse($lowerMessage);

        return response()->json([
            'status' => 'success',
            'response' => $response,
            'timestamp' => now()->format('H:i')
        ]);
    }

    private function getAiResponse($message)
    {
        // 1. Basic Greetings
        if (Str::contains($message, ['hi', 'hello', 'hey', 'greetings'])) {
            return "Hello! I'm your Alpha Health Group Assistant. How can I help you today with our healthcare consultancies or services?";
        }

        // 2. Services inquiry
        if (Str::contains($message, ['service', 'what do you do', 'expertise'])) {
            $services = Service::limit(3)->pluck('name')->toArray();
            $servicesList = implode(', ', $services);
            return "We offer a wide range of healthcare consultancy services, including " . $servicesList . " and much more. Is there a specific service you're interested in?";
        }

        // 3. Contact/Location
        if (Str::contains($message, ['contact', 'location', 'where', 'phone', 'call', 'email'])) {
            return "You can contact us via email at info@alphahmc.com or visit our Contact page for more details. Would you like me to show you the contact information?";
        }

        // 4. Appointment/Booking
        if (Str::contains($message, ['book', 'appointment', 'schedule', 'consultation'])) {
            return "To schedule a consultation, you can fill out the inquiry form on our website or call us directly. I can help you find the right service for your needs first!";
        }

        // 5. About Alpha Health
        if (Str::contains($message, ['about', 'who are you', 'alpha health'])) {
            return "Alpha Health Group is a leading healthcare consultancy in Dubai, providing premium healthcare solutions, facility licensing, and professional services across the UAE.";
        }

        // 6. Search for specific services if not matched
        $matchedService = Service::where('name', 'like', '%' . $message . '%')->first();
        if ($matchedService) {
            return "Yes, we provide " . $matchedService->name . ". " . Str::limit(strip_tags($matchedService->overview), 150) . " Would you like to view more details about this service?";
        }

        // 7. Fallback
        return "That's an interesting question! While I'm still learning, I can definitely help you with information about our healthcare services, licensing, or how to contact our experts. Could you please specify your requirement?";
    }
}
