<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CarListingAssistantService
{
    /**
     * Generate structured car listing content.
     */
    public function generate(array $params): array
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model') ?? 'gemini-2.5-flash';

        if (!$apiKey) {
            Log::info('Gemini API key not configured. Using Mock AI generator.', ['params' => $params]);
            return $this->generateMockResponse($params);
        }

        $prompt = $this->buildPrompt($params);

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $apiKey;
            
            $response = Http::timeout(10)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ]
                ]);

            if ($response->successful()) {
                $json = $response->json();
                $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                
                if ($text) {
                    $decoded = json_decode($text, true);
                    if (isset($decoded['title']) && isset($decoded['description']) && isset($decoded['highlights'])) {
                        Log::info('AI car listing content generated successfully via Gemini API.');
                        return $decoded;
                    }
                }
            }

            Log::warning('Gemini API request failed or returned invalid format. Falling back to Mock generator.', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

        } catch (\Throwable $e) {
            Log::error('Error calling Gemini API. Falling back to Mock generator.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $this->generateMockResponse($params);
    }

    /**
     * Build the prompt for Gemini API.
     */
    private function buildPrompt(array $params): string
    {
        $carInfo = json_encode($params, JSON_PRETTY_PRINT);

        return <<<PROMPT
You are a professional automotive marketing writer.
Based on the following car specifications provided by a dealer, generate a high-quality, professional showroom listing representation.

Car Specifications:
{$carInfo}

Rules:
- Create an attractive but realistic car title based ONLY on the provided brand, model, and year.
- Write a professional showroom description using marketing language suitable for selling cars.
- Mention ONLY the provided information in the specifications.
- Do NOT invent mileage, engine information, accident history, or specifications that are not explicitly present in the provided Car Specifications.
- Return ONLY valid JSON matching the following schema. Do not include any markdown format tags like ```json or other text. Return ONLY the raw JSON string:

{
  "title": "Realistic title",
  "description": "Showroom description",
  "highlights": [
    "Highlight point 1 based ONLY on provided specs",
    "Highlight point 2 based ONLY on provided specs",
    "Highlight point 3 based ONLY on provided specs"
  ]
}
PROMPT;
    }

    private function generateMockResponse(array $params): array
    {
        $brand = !empty($params['brand']) ? $params['brand'] : 'Premium';
        $model = !empty($params['model']) ? $params['model'] : 'Vehicle';
        $year = !empty($params['year']) ? $params['year'] : date('Y');
        $price = !empty($params['price']) ? number_format($params['price']) : '50,000';
        
        $title = "Stunning {$year} {$brand} {$model} - Showroom Condition";
        
        $description = "Presenting a beautiful {$year} {$brand} {$model} in excellent condition. This premium vehicle represents the perfect blend of performance, style, and reliability. Priced at only \${$price}, it offers an exceptional deal on the market. Meticulously inspected by our certified technicians, it is showroom-ready and features a premium design with full comfort options.";

        $highlights = [
            "Premium {$year} {$brand} {$model} in showroom-ready condition",
            "Certified multi-point quality and safety check completed",
            "Offered at a competitive marketplace value of \${$price}"
        ];

        return [
            'title' => $title,
            'description' => $description,
            'highlights' => $highlights,
        ];
    }
}
