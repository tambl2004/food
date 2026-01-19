<?php

namespace App\Services;

use App\Models\Ingredient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CameraVisionService
{
    /**
     * Nhận diện nguyên liệu từ hình ảnh
     * 
     * @param string $imagePath Đường dẫn đến file ảnh hoặc base64 image data
     * @return array Danh sách nguyên liệu được nhận diện với confidence score
     */
    public function detectIngredients($imagePath): array
    {
        try {
            // TODO: Tích hợp với AI Vision API thực tế (Gemini Vision, OpenAI Vision, etc.)
            // Hiện tại sử dụng mock data để demo
            
            // Nếu có API key, có thể sử dụng Gemini Vision API:
            // return $this->detectWithGemini($imagePath);
            
            // Hoặc OpenAI Vision API:
            // return $this->detectWithOpenAI($imagePath);
            
            // Mock detection - trả về một số nguyên liệu phổ biến
            return $this->mockDetection($imagePath);
            
        } catch (\Exception $e) {
            Log::error('Camera Vision Detection Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mock detection - trả về danh sách nguyên liệu mẫu
     * Trong production, thay thế bằng API thực tế
     */
    private function mockDetection($imagePath): array
    {
        // Lấy danh sách nguyên liệu phổ biến từ database
        $commonIngredients = Ingredient::where('status', 'active')
            ->inRandomOrder()
            ->limit(rand(2, 5))
            ->get();

        $detections = [];
        foreach ($commonIngredients as $ingredient) {
            $detections[] = [
                'ingredient_id' => $ingredient->id,
                'ingredient_name' => $ingredient->name,
                'confidence_score' => rand(70, 95) / 100, // 70-95% confidence
            ];
        }

        return $detections;
    }

    /**
     * Detect với Gemini Vision API (ví dụ)
     * Cần cấu hình GEMINI_API_KEY trong .env
     */
    private function detectWithGemini($imagePath): array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return $this->mockDetection($imagePath);
        }

        try {
            // Đọc ảnh và chuyển sang base64
            $imageData = base64_encode(file_get_contents($imagePath));
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-vision:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => 'Nhận diện các nguyên liệu thực phẩm trong ảnh này. Trả về danh sách tên nguyên liệu bằng tiếng Việt, mỗi nguyên liệu một dòng.'
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => 'image/jpeg',
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Parse kết quả và map với database
                return $this->parseGeminiResponse($text);
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
        }

        return $this->mockDetection($imagePath);
    }

    /**
     * Parse response từ Gemini và map với ingredients trong database
     */
    private function parseGeminiResponse(string $text): array
    {
        $lines = explode("\n", $text);
        $detections = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Tìm nguyên liệu trong database (fuzzy match)
            $ingredient = Ingredient::where('status', 'active')
                ->where('name', 'like', '%' . $line . '%')
                ->first();

            if ($ingredient) {
                $detections[] = [
                    'ingredient_id' => $ingredient->id,
                    'ingredient_name' => $ingredient->name,
                    'confidence_score' => 0.85, // Default confidence
                ];
            }
        }

        return $detections;
    }

    /**
     * Ánh xạ tên nguyên liệu được nhận diện với database
     * 
     * @param array $detectedNames Danh sách tên nguyên liệu
     * @return array Danh sách với ingredient_id và confidence
     */
    public function mapIngredients(array $detectedNames): array
    {
        $mapped = [];

        foreach ($detectedNames as $name => $confidence) {
            // Tìm kiếm chính xác trước
            $ingredient = Ingredient::where('status', 'active')
                ->where('name', $name)
                ->first();

            // Nếu không tìm thấy, tìm kiếm gần đúng
            if (!$ingredient) {
                $ingredient = Ingredient::where('status', 'active')
                    ->where('name', 'like', '%' . $name . '%')
                    ->first();
            }

            if ($ingredient) {
                $mapped[] = [
                    'ingredient_id' => $ingredient->id,
                    'ingredient_name' => $ingredient->name,
                    'confidence_score' => is_numeric($confidence) ? $confidence : 0.8,
                ];
            }
        }

        return $mapped;
    }
}

