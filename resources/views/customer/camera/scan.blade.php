@extends('layouts.customer')

@section('title', 'Quét nguyên liệu bằng Camera AI')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
    
    #videoElement {
        width: 100%;
        max-width: 640px;
        height: auto;
        border-radius: 12px;
        transform: scaleX(-1); /* Mirror effect */
    }
    
    #capturedImage {
        width: 100%;
        max-width: 640px;
        height: auto;
        border-radius: 12px;
        display: none;
    }
    
    .camera-container {
        position: relative;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .camera-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        border: 2px dashed rgba(255, 255, 255, 0.5);
        border-radius: 12px;
    }
    
    .detection-item {
        transition: all 0.3s ease;
    }
    
    .detection-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .nutrition-chart-container {
        position: relative;
        height: 200px;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-camera text-white text-xl"></i>
                </div>
                <span>Quét nguyên liệu bằng Camera AI</span>
            </h1>
            <p class="text-gray-600 text-lg">Chụp ảnh nguyên liệu và để AI nhận diện tự động</p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Camera & Detection -->
            <div class="space-y-6">
                <!-- Camera View Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-video text-blue-500"></i>
                        Camera View
                    </h2>
                    
                    <div class="camera-container mb-4">
                        <video id="videoElement" autoplay playsinline></video>
                        <canvas id="canvasElement" style="display: none;"></canvas>
                        <img id="capturedImage" alt="Captured image">
                        <div class="camera-overlay"></div>
                    </div>
                    
                    <div class="flex gap-3 justify-center">
                        <button id="captureBtn" onclick="captureImage()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-camera"></i>
                            <span>Chụp ảnh</span>
                        </button>
                        <button id="retakeBtn" onclick="retakePhoto()" class="px-6 py-3 bg-gray-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2" style="display: none;">
                            <i class="fas fa-redo"></i>
                            <span>Chụp lại</span>
                        </button>
                        <button id="analyzeBtn" onclick="analyzeImage()" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2" style="display: none;">
                            <i class="fas fa-search"></i>
                            <span>Phân tích</span>
                        </button>
                    </div>
                    
                    <div id="cameraError" class="mt-4 text-red-600 text-sm" style="display: none;"></div>
                </div>

                <!-- Detection Results Section -->
                <div id="detectionResults" class="bg-white rounded-2xl shadow-lg p-6" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        Kết quả nhận diện
                    </h2>
                    
                    <div id="detectionList" class="space-y-3 mb-4">
                        <!-- Detection items will be inserted here -->
                    </div>
                    
                    <button onclick="confirmIngredients()" class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-check me-2"></i>
                        Xác nhận nguyên liệu
                    </button>
                </div>
            </div>

            <!-- Right Column: Nutrition & Suggestions -->
            <div class="space-y-6">
                <!-- Nutrition Analysis Panel -->
                <div id="nutritionPanel" class="bg-white rounded-2xl shadow-lg p-6" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-orange-500"></i>
                        Phân tích dinh dưỡng
                    </h2>
                    
                    <div class="mb-4">
                        <div class="text-center p-4 bg-gradient-to-r from-orange-100 to-yellow-100 rounded-xl mb-4">
                            <p class="text-sm text-gray-600 mb-1">Tổng năng lượng</p>
                            <p class="text-4xl font-bold text-orange-600" id="totalCalories">0</p>
                            <p class="text-sm text-gray-600">kcal</p>
                        </div>
                        
                        <div class="nutrition-chart-container">
                            <canvas id="nutritionChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-xl">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Nhận xét AI:</p>
                        <p class="text-gray-600" id="nutritionComment">-</p>
                    </div>
                </div>

                <!-- Dish Suggestions -->
                <div id="dishSuggestions" class="bg-white rounded-2xl shadow-lg p-6" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-utensils text-red-500"></i>
                        Gợi ý món ăn
                    </h2>
                    
                    <div id="suggestionsList" class="space-y-4">
                        <!-- Dish suggestions will be inserted here -->
                    </div>
                </div>

                <!-- Quick Actions -->
                <div id="quickActions" class="bg-white rounded-2xl shadow-lg p-6" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Hành động nhanh</h2>
                    
                    <div class="space-y-3">
                        <button onclick="saveToMyKitchen()" class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i>
                            <span>Thêm vào bếp của tôi</span>
                        </button>
                        <a href="{{ route('user.ingredients.find-dishes') }}" class="block w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center">
                            <i class="fas fa-search me-2"></i>
                            Xem món gợi ý
                        </a>
                        <button onclick="viewNutritionDetails()" class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            <span>Xem chi tiết dinh dưỡng</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl p-8 text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-500 mx-auto mb-4"></div>
        <p class="text-gray-700 font-semibold" id="loadingText">Đang xử lý...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let stream = null;
    let capturedImageData = null;
    let detectedIngredients = [];
    let confirmedIngredientIds = [];
    let nutritionChart = null;

    // Initialize camera on page load
    document.addEventListener('DOMContentLoaded', function() {
        initCamera();
        setupCSRF();
    });

    // Setup CSRF token for AJAX requests
    function setupCSRF() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    }

    // Initialize camera
    async function initCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'environment', // Prefer back camera on mobile
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            });
            const video = document.getElementById('videoElement');
            video.srcObject = stream;
        } catch (err) {
            console.error('Error accessing camera:', err);
            document.getElementById('cameraError').textContent = 'Không thể truy cập camera. Vui lòng kiểm tra quyền truy cập.';
            document.getElementById('cameraError').style.display = 'block';
        }
    }

    // Capture image from video
    function captureImage() {
        const video = document.getElementById('videoElement');
        const canvas = document.getElementById('canvasElement');
        const capturedImg = document.getElementById('capturedImage');
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0);
        
        // Stop video stream
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        
        // Show captured image
        capturedImageData = canvas.toDataURL('image/jpeg');
        capturedImg.src = capturedImageData;
        capturedImg.style.display = 'block';
        video.style.display = 'none';
        
        // Show/hide buttons
        document.getElementById('captureBtn').style.display = 'none';
        document.getElementById('retakeBtn').style.display = 'inline-flex';
        document.getElementById('analyzeBtn').style.display = 'inline-flex';
    }

    // Retake photo
    function retakePhoto() {
        const video = document.getElementById('videoElement');
        const capturedImg = document.getElementById('capturedImage');
        
        capturedImg.style.display = 'none';
        video.style.display = 'block';
        
        document.getElementById('captureBtn').style.display = 'inline-flex';
        document.getElementById('retakeBtn').style.display = 'none';
        document.getElementById('analyzeBtn').style.display = 'none';
        
        // Hide results
        document.getElementById('detectionResults').style.display = 'none';
        document.getElementById('nutritionPanel').style.display = 'none';
        document.getElementById('dishSuggestions').style.display = 'none';
        document.getElementById('quickActions').style.display = 'none';
        
        // Reinitialize camera
        initCamera();
    }

    // Analyze image
    async function analyzeImage() {
        if (!capturedImageData) {
            showCustomerToast('error', 'Lỗi', 'Vui lòng chụp ảnh trước!');
            return;
        }

        showLoading('Đang nhận diện nguyên liệu...');

        try {
            // Convert base64 to blob
            const blob = await base64ToBlob(capturedImageData);
            const formData = new FormData();
            formData.append('image', blob, 'capture.jpg');
            formData.append('save_history', 'true');

            const response = await fetch('{{ route("api.ai.vision.ingredients") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const data = await response.json();

            hideLoading();

            if (data.success && data.data.detections.length > 0) {
                detectedIngredients = data.data.detections;
                displayDetectionResults(data.data.detections);
                showCustomerToast('success', 'Thành công', `Đã nhận diện ${data.data.detections.length} nguyên liệu!`);
            } else {
                showCustomerToast('warning', 'Cảnh báo', 'Không nhận diện được nguyên liệu nào. Vui lòng thử lại!');
            }
        } catch (error) {
            hideLoading();
            console.error('Error analyzing image:', error);
            showCustomerToast('error', 'Lỗi', 'Có lỗi xảy ra khi phân tích ảnh!');
        }
    }

    // Display detection results
    function displayDetectionResults(detections) {
        const container = document.getElementById('detectionList');
        container.innerHTML = '';

        detections.forEach((detection, index) => {
            const confidencePercent = Math.round(detection.confidence_score * 100);
            const item = document.createElement('div');
            item.className = 'detection-item p-4 bg-gray-50 rounded-xl border-2 border-transparent';
            item.dataset.ingredientId = detection.ingredient_id;
            item.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               class="ingredient-checkbox w-5 h-5 text-blue-600 rounded" 
                               checked 
                               data-ingredient-id="${detection.ingredient_id}"
                               onchange="updateConfirmedIngredients()">
                        <div>
                            <p class="font-semibold text-gray-900">${detection.ingredient_name}</p>
                            <p class="text-sm text-gray-500">Độ tin cậy: ${confidencePercent}%</p>
                        </div>
                    </div>
                    <div class="w-16 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: ${confidencePercent}%"></div>
                    </div>
                </div>
            `;
            container.appendChild(item);
        });

        // Initialize confirmed ingredients
        updateConfirmedIngredients();
        document.getElementById('detectionResults').style.display = 'block';
    }

    // Update confirmed ingredient IDs
    function updateConfirmedIngredients() {
        const checkboxes = document.querySelectorAll('.ingredient-checkbox:checked');
        confirmedIngredientIds = Array.from(checkboxes).map(cb => parseInt(cb.dataset.ingredientId));
    }

    // Confirm ingredients and analyze nutrition
    async function confirmIngredients() {
        if (confirmedIngredientIds.length === 0) {
            showCustomerToast('warning', 'Cảnh báo', 'Vui lòng chọn ít nhất một nguyên liệu!');
            return;
        }

        showLoading('Đang phân tích dinh dưỡng...');

        try {
            // Analyze nutrition
            const nutritionResponse = await fetch('{{ route("api.ai.nutrition-analysis") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    ingredient_ids: confirmedIngredientIds
                })
            });

            const nutritionData = await nutritionResponse.json();

            if (nutritionData.success) {
                displayNutritionAnalysis(nutritionData.data);
            }

            // Get dish suggestions
            showLoading('Đang tìm món ăn phù hợp...');
            const dishesResponse = await fetch('{{ route("api.ai.suggest-dishes") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    ingredient_ids: confirmedIngredientIds
                })
            });

            const dishesData = await dishesResponse.json();

            hideLoading();

            if (dishesData.success) {
                displayDishSuggestions(dishesData.data);
            }

            document.getElementById('quickActions').style.display = 'block';
            showCustomerToast('success', 'Thành công', 'Đã phân tích xong!');
        } catch (error) {
            hideLoading();
            console.error('Error confirming ingredients:', error);
            showCustomerToast('error', 'Lỗi', 'Có lỗi xảy ra!');
        }
    }

    // Display nutrition analysis
    function displayNutritionAnalysis(data) {
        document.getElementById('totalCalories').textContent = Math.round(data.total_calories);
        document.getElementById('nutritionComment').textContent = data.nutrition_comment || 'Cân bằng dinh dưỡng';

        // Create nutrition chart
        const ctx = document.getElementById('nutritionChart').getContext('2d');
        
        if (nutritionChart) {
            nutritionChart.destroy();
        }

        nutritionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Protein', 'Chất béo', 'Carb'],
                datasets: [{
                    data: [data.protein, data.fat, data.carbs],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(34, 197, 94, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        document.getElementById('nutritionPanel').style.display = 'block';
    }

    // Display dish suggestions
    function displayDishSuggestions(suggestions) {
        const container = document.getElementById('suggestionsList');
        container.innerHTML = '';

        if (suggestions.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-4">Không tìm thấy món ăn phù hợp.</p>';
            return;
        }

        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-lg transition-all';
            item.innerHTML = `
                <div class="flex items-start gap-4">
                    ${suggestion.dish_image ? `
                        <img src="${suggestion.dish_image}" alt="${suggestion.dish_name}" class="w-20 h-20 object-cover rounded-lg">
                    ` : `
                        <div class="w-20 h-20 bg-gray-300 rounded-lg flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-500 text-2xl"></i>
                        </div>
                    `}
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-1">${suggestion.dish_name}</h3>
                        <p class="text-sm text-gray-600 mb-2">${suggestion.reason}</p>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: ${suggestion.match_rate}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">${suggestion.match_rate}%</span>
                        </div>
                        ${suggestion.missing_ingredients.length > 0 ? `
                            <p class="text-xs text-orange-600 mb-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                Thiếu ${suggestion.missing_ingredients.length} nguyên liệu
                            </p>
                        ` : `
                            <p class="text-xs text-green-600">
                                <i class="fas fa-check-circle"></i>
                                Đủ nguyên liệu!
                            </p>
                        `}
                        <a href="/dishes/${suggestion.dish_slug}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold mt-2 inline-block">
                            Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            `;
            container.appendChild(item);
        });

        document.getElementById('dishSuggestions').style.display = 'block';
    }

    // Save ingredients to user's kitchen
    async function saveToMyKitchen() {
        if (confirmedIngredientIds.length === 0) {
            showCustomerToast('warning', 'Cảnh báo', 'Vui lòng chọn nguyên liệu trước!');
            return;
        }

        showLoading('Đang lưu nguyên liệu...');

        try {
            const response = await fetch('{{ route("api.user.ingredients.from-camera") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    ingredient_ids: confirmedIngredientIds
                })
            });

            const data = await response.json();
            hideLoading();

            if (data.success) {
                showCustomerToast('success', 'Thành công', data.message);
                setTimeout(() => {
                    window.location.href = '{{ route("user.ingredients.index") }}';
                }, 1500);
            } else {
                showCustomerToast('error', 'Lỗi', data.message || 'Có lỗi xảy ra!');
            }
        } catch (error) {
            hideLoading();
            console.error('Error saving ingredients:', error);
            showCustomerToast('error', 'Lỗi', 'Có lỗi xảy ra khi lưu nguyên liệu!');
        }
    }

    // View nutrition details
    function viewNutritionDetails() {
        // Scroll to nutrition panel
        document.getElementById('nutritionPanel').scrollIntoView({ behavior: 'smooth' });
    }

    // Utility functions
    function base64ToBlob(base64) {
        const parts = base64.split(';base64,');
        const contentType = parts[0].split(':')[1];
        const raw = window.atob(parts[1]);
        const rawLength = raw.length;
        const uInt8Array = new Uint8Array(rawLength);

        for (let i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }

        return new Blob([uInt8Array], { type: contentType });
    }

    function showLoading(text = 'Đang xử lý...') {
        document.getElementById('loadingText').textContent = text;
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endpush

