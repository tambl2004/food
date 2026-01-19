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
     * @param string $imagePath Đường dẫn đến file ảnh
     * @return array Danh sách nguyên liệu được nhận diện với confidence score
     */
    public function detectIngredients($imagePath): array
    {
        try {
            $apiKey = env('GEMINI_API_KEY');
            
            // Nếu có API key, sử dụng Gemini Vision API
            if ($apiKey) {
                return $this->detectWithGemini($imagePath);
            }
            
            // Fallback về mock detection nếu không có API key
            Log::warning('GEMINI_API_KEY chưa được cấu hình, sử dụng mock detection');
            return $this->mockDetection($imagePath);
            
        } catch (\Exception $e) {
            Log::error('Camera Vision Detection Error: ' . $e->getMessage());
            // Fallback về mock nếu có lỗi
            return $this->mockDetection($imagePath);
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
     * Detect với Gemini Vision API
     * Cần cấu hình GEMINI_API_KEY trong .env
     */
    private function detectWithGemini($imagePath): array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return $this->mockDetection($imagePath);
        }

        try {
            // Lấy danh sách tất cả nguyên liệu trong database để gửi cho AI
            $allIngredients = Ingredient::where('status', 'active')
                ->orderBy('name')
                ->pluck('name')
                ->toArray();
            
            $ingredientsList = implode(', ', array_slice($allIngredients, 0, 200)); // Giới hạn 200 để tránh prompt quá dài
            
            // Đọc ảnh và chuyển sang base64
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = $this->getMimeType($imagePath);
            
            // Prompt chi tiết để AI trả về đúng tên nguyên liệu trong database
            $prompt = "Bạn là chuyên gia nhận diện nguyên liệu thực phẩm. Hãy phân tích hình ảnh và liệt kê TẤT CẢ các nguyên liệu thực phẩm có trong ảnh.

QUAN TRỌNG: Chỉ trả về tên nguyên liệu bằng tiếng Việt, mỗi nguyên liệu một dòng. Tên nguyên liệu phải CHÍNH XÁC với một trong các tên sau đây trong database:

{$ingredientsList}

Yêu cầu:
1. Chỉ liệt kê nguyên liệu thực phẩm (không phải đồ vật, dụng cụ)
2. Tên nguyên liệu phải khớp chính xác với danh sách trên
3. Nếu không chắc chắn, hãy bỏ qua nguyên liệu đó
4. Trả về dạng danh sách, mỗi dòng một nguyên liệu
5. Không thêm số thứ tự, ký tự đặc biệt, chỉ tên nguyên liệu

Ví dụ format trả về:
Thịt gà
Cà chua
Hành tây
Rau mùi";

            // Sử dụng model mới nhất
            $model = 'gemini-2.5-flash';
            
            // Tắt SSL verification cho local development
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
                        'temperature' => 0.3, // Thấp hơn để kết quả chính xác hơn
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ]
                ]
            );

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                Log::info('Gemini API Response', ['text' => $text]);
                
                // Parse kết quả và map với database
                return $this->parseGeminiResponse($text);
            } else {
                $error = $response->json();
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'error' => $error,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
        }

        // Fallback về mock nếu có lỗi
        return $this->mockDetection($imagePath);
    }

    /**
     * Lấy MIME type của file ảnh
     */
    private function getMimeType($imagePath): string
    {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];
        
        return $mimeTypes[$extension] ?? 'image/jpeg';
    }

    /**
     * Parse response từ Gemini và map với ingredients trong database
     */
    private function parseGeminiResponse(string $text): array
    {
        $lines = explode("\n", $text);
        $detections = [];
        $foundIngredientIds = []; // Tránh trùng lặp

        // Lấy tất cả nguyên liệu active để so sánh
        $allIngredients = Ingredient::where('status', 'active')
            ->get(['id', 'name']);

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Loại bỏ số thứ tự, ký tự đặc biệt ở đầu dòng
            $line = preg_replace('/^[\d\.\-\*\s]+/', '', $line);
            $line = trim($line);
            
            if (empty($line) || strlen($line) < 2) continue;

            // Tìm kiếm chính xác trước
            $ingredient = $allIngredients->first(function($ing) use ($line) {
                return mb_strtolower($ing->name) === mb_strtolower($line);
            });

            // Nếu không tìm thấy chính xác, tìm kiếm gần đúng (contains)
            if (!$ingredient) {
                $ingredient = $allIngredients->first(function($ing) use ($line) {
                    return mb_stripos($ing->name, $line) !== false || 
                           mb_stripos($line, $ing->name) !== false;
                });
            }

            // Nếu vẫn không tìm thấy, thử tìm kiếm từng từ
            if (!$ingredient) {
                $words = explode(' ', $line);
                foreach ($words as $word) {
                    if (strlen($word) < 3) continue;
                    $ingredient = $allIngredients->first(function($ing) use ($word) {
                        return mb_stripos($ing->name, $word) !== false;
                    });
                    if ($ingredient) break;
                }
            }

            // Nếu tìm thấy và chưa có trong danh sách
            if ($ingredient && !in_array($ingredient->id, $foundIngredientIds)) {
                $foundIngredientIds[] = $ingredient->id;
                
                // Tính confidence dựa trên độ khớp
                $confidence = $this->calculateConfidence($line, $ingredient->name);
                
                $detections[] = [
                    'ingredient_id' => $ingredient->id,
                    'ingredient_name' => $ingredient->name,
                    'confidence_score' => $confidence,
                    'detected_name' => $line, // Tên AI phát hiện (để debug)
                ];
            }
        }

        // Sắp xếp theo confidence giảm dần
        usort($detections, function($a, $b) {
            return $b['confidence_score'] <=> $a['confidence_score'];
        });

        return $detections;
    }

    /**
     * Tính confidence score dựa trên độ khớp tên
     */
    private function calculateConfidence(string $detectedName, string $dbName): float
    {
        $detectedName = mb_strtolower(trim($detectedName));
        $dbName = mb_strtolower(trim($dbName));
        
        // Khớp chính xác
        if ($detectedName === $dbName) {
            return 0.95;
        }
        
        // Khớp một phần
        if (mb_stripos($dbName, $detectedName) !== false || 
            mb_stripos($detectedName, $dbName) !== false) {
            $similarity = similar_text($detectedName, $dbName) / max(strlen($detectedName), strlen($dbName));
            return max(0.70, min(0.90, $similarity));
        }
        
        // Khớp từng từ
        $detectedWords = explode(' ', $detectedName);
        $dbWords = explode(' ', $dbName);
        $matchedWords = 0;
        
        foreach ($detectedWords as $word) {
            if (strlen($word) < 3) continue;
            foreach ($dbWords as $dbWord) {
                if (mb_stripos($dbWord, $word) !== false || mb_stripos($word, $dbWord) !== false) {
                    $matchedWords++;
                    break;
                }
            }
        }
        
        if ($matchedWords > 0) {
            $ratio = $matchedWords / max(count($detectedWords), count($dbWords));
            return max(0.65, min(0.85, $ratio * 0.9));
        }
        
        return 0.70; // Default confidence
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

