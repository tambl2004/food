<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GeminiTestController extends Controller
{
    /**
     * Hiển thị trang demo test Gemini API
     */
    public function index(): View
    {
        $apiKey = env('GEMINI_API_KEY');
        $hasApiKey = !empty($apiKey);
        $apiKeyPreview = $hasApiKey ? substr($apiKey, 0, 10) . '...' : 'Chưa cấu hình';

        return view('demo.gemini-test', [
            'hasApiKey' => $hasApiKey,
            'apiKeyPreview' => $apiKeyPreview,
        ]);
    }

    /**
     * Test Gemini API với text prompt
     * POST /api/test/gemini/text
     */
    public function testText(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:1000'],
            'model' => ['nullable', 'string', 'max:100'],
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'GEMINI_API_KEY chưa được cấu hình trong file .env',
            ], 400);
        }

        // Sử dụng model được chọn hoặc mặc định
        $model = $request->input('model', 'gemini-2.5-flash');

        try {
            // Tắt SSL verification cho local development (Windows/MAMP)
            // Luôn tắt verify cho development để tránh lỗi SSL certificate
            $httpClient = Http::timeout(30)->withoutVerifying();
            
            $response = $httpClient->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $request->prompt
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ]
                ]
            );

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi';

                return response()->json([
                    'success' => true,
                    'data' => [
                        'response' => $text,
                        'full_response' => $result,
                    ],
                ]);
            } else {
                $error = $response->json();
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'response' => $error,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi từ Gemini API: ' . ($error['error']['message'] ?? 'Unknown error'),
                    'status' => $response->status(),
                    'error' => $error,
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gọi API: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test Gemini API với hình ảnh
     * POST /api/test/gemini/image
     */
    public function testImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:10240'], // Max 10MB
            'prompt' => ['nullable', 'string', 'max:500'],
            'model' => ['nullable', 'string', 'max:100'],
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'GEMINI_API_KEY chưa được cấu hình trong file .env',
            ], 400);
        }

        // Sử dụng model được chọn hoặc mặc định
        $model = $request->input('model', 'gemini-2.5-flash');

        try {
            // Đọc ảnh và chuyển sang base64
            $imageData = base64_encode(file_get_contents($request->file('image')->getRealPath()));
            $mimeType = $request->file('image')->getMimeType();

            $prompt = $request->input('prompt', 'Mô tả chi tiết hình ảnh này bằng tiếng Việt.');

            // Tắt SSL verification cho local development (Windows/MAMP)
            // Luôn tắt verify cho development để tránh lỗi SSL certificate
            $httpClient = Http::timeout(30)->withoutVerifying();
            
            $response = $httpClient->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data' => $imageData
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ]
                ]
            );

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi';

                return response()->json([
                    'success' => true,
                    'data' => [
                        'response' => $text,
                        'full_response' => $result,
                    ],
                ]);
            } else {
                $error = $response->json();
                Log::error('Gemini Vision API Error', [
                    'status' => $response->status(),
                    'response' => $error,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi từ Gemini API: ' . ($error['error']['message'] ?? 'Unknown error'),
                    'status' => $response->status(),
                    'error' => $error,
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Gemini Vision API Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gọi API: ' . $e->getMessage(),
            ], 500);
        }
    }
}

