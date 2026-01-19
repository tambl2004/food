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
        border-radius: 16px;
        transform: scaleX(-1); /* Mirror effect */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    #capturedImage {
        width: 100%;
        max-width: 640px;
        height: auto;
        border-radius: 16px;
        display: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .camera-container {
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        overflow: hidden;
        padding: 4px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .camera-container video,
    .camera-container img {
        background: #000;
        border-radius: 12px;
    }
    
    .camera-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 60%;
        pointer-events: none;
        border: 3px dashed rgba(255, 255, 255, 0.6);
        border-radius: 12px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 0.9; }
    }
    
    .detection-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        animation: slideIn 0.4s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .detection-item:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .detection-item.checked {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-color: #3b82f6;
    }
    
    .nutrition-chart-container {
        position: relative;
        height: 250px;
    }
    
    .btn-primary-camera {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
    }
    
    .btn-primary-camera:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-success-camera {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        transition: all 0.3s ease;
    }
    
    .btn-success-camera:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }
    
    .card-glow {
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
    }
    
    .card-glow:hover {
        box-shadow: 0 0 30px rgba(102, 126, 234, 0.2);
    }
    
    .confidence-bar {
        height: 8px;
        border-radius: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
        transition: width 0.5s ease;
    }
    
    .dish-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .dish-card:hover {
        transform: translateX(4px);
        border-left-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .loading-pulse {
        animation: pulse-loading 1.5s ease-in-out infinite;
    }
    
    @keyframes pulse-loading {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 20px;
    }
    
    .step {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .step.active {
        background: #3b82f6;
        width: 32px;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl mb-4">
                <i class="fas fa-camera text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                Quét nguyên liệu bằng Camera AI
            </h1>
            <p class="text-gray-600 text-lg">Chụp ảnh nguyên liệu và để AI nhận diện tự động với độ chính xác cao</p>
            
            <!-- API Status Indicator -->
            <div class="mt-4 flex justify-center">
                <div class="api-status-badge inline-flex items-center gap-2 px-5 py-2.5 rounded-full {{ $hasApiKey ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-800 border-2 border-green-300' : 'bg-gradient-to-r from-red-50 to-orange-50 text-red-800 border-2 border-red-300' }} shadow-md">
                    @if($hasApiKey)
                        <div class="w-3 h-3 bg-green-500 rounded-full pulse-dot"></div>
                        <span class="text-sm font-bold">
                            <i class="fas fa-check-circle me-1.5"></i>
                            Gemini API đang hoạt động
                        </span>
                        @if($apiKeyPreview)
                            <span class="text-xs opacity-60 font-medium ml-1">({{ $apiKeyPreview }})</span>
                        @endif
                    @else
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm font-bold">
                            <i class="fas fa-exclamation-triangle me-1.5"></i>
                            Gemini API chưa được cấu hình
                        </span>
                        <span class="text-xs opacity-75 ml-2">(Sử dụng mock data)</span>
                    @endif
                </div>
            </div>
            
            <!-- Step Indicator -->
            <div class="step-indicator mt-6">
                <div class="step active" id="step1"></div>
                <div class="step" id="step2"></div>
                <div class="step" id="step3"></div>
                <div class="step" id="step4"></div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Camera & Detection -->
            <div class="space-y-6">
                <!-- Camera View Section -->
                <div class="bg-white rounded-2xl shadow-xl p-6 card-glow">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-video text-white"></i>
                        </div>
                        <span>Camera View</span>
                    </h2>
                    
                    <div class="camera-container mb-4">
                        <video id="videoElement" autoplay playsinline></video>
                        <canvas id="canvasElement" style="display: none;"></canvas>
                        <img id="capturedImage" alt="Captured image">
                        <div class="camera-overlay"></div>
                    </div>
                    
                    <div class="flex gap-3 justify-center flex-wrap">
                        <button id="captureBtn" onclick="captureImage()" class="px-8 py-3 btn-primary-camera text-white rounded-xl font-semibold shadow-lg flex items-center gap-2">
                            <i class="fas fa-camera text-lg"></i>
                            <span>Chụp ảnh</span>
                        </button>
                        <button id="retakeBtn" onclick="retakePhoto()" class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2" style="display: none;">
                            <i class="fas fa-redo"></i>
                            <span>Chụp lại</span>
                        </button>
                        <button id="analyzeBtn" onclick="analyzeImage()" class="px-8 py-3 btn-success-camera text-white rounded-xl font-semibold shadow-lg flex items-center gap-2" style="display: none;">
                            <i class="fas fa-magic text-lg"></i>
                            <span>Phân tích AI</span>
                        </button>
                    </div>
                    
                    <div id="cameraError" class="mt-4 text-red-600 text-sm" style="display: none;"></div>
                </div>

                <!-- Detection Results Section -->
                <div id="detectionResults" class="bg-white rounded-2xl shadow-xl p-6 card-glow" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <span>Kết quả nhận diện</span>
                        <span id="detectionCount" class="ml-auto text-sm font-normal text-gray-500"></span>
                    </h2>
                    
                    <div id="detectionList" class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                        <!-- Detection items will be inserted here -->
                    </div>
                    
                    <button onclick="confirmIngredients()" class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Xác nhận và phân tích dinh dưỡng</span>
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
        
        // Update step indicator
        updateStepIndicator(1);
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
        
        // Reset step indicator
        updateStepIndicator(1);
        
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
                updateStepIndicator(2);
            } else {
                showCustomerToast('warning', 'Cảnh báo', 'Không nhận diện được nguyên liệu nào. Vui lòng thử lại với ảnh rõ hơn!');
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

        if (detections.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-500 py-4">Không nhận diện được nguyên liệu nào.</p>';
            document.getElementById('detectionResults').style.display = 'block';
            return;
        }

        // Update count
        document.getElementById('detectionCount').textContent = `${detections.length} nguyên liệu`;

        detections.forEach((detection, index) => {
            const confidencePercent = Math.round(detection.confidence_score * 100);
            const item = document.createElement('div');
            item.className = 'detection-item p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border-2 border-gray-200 hover:border-blue-400';
            item.dataset.ingredientId = detection.ingredient_id;
            item.style.animationDelay = `${index * 0.1}s`;
            
            // Show detected name if different from database name
            const detectedNameInfo = detection.detected_name && detection.detected_name !== detection.ingredient_name
                ? `<p class="text-xs text-blue-600 mt-1">
                    <i class="fas fa-eye me-1"></i>
                    AI phát hiện: "${detection.detected_name}"
                   </p>`
                : '';
            
            item.innerHTML = `
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-1">
                        <input type="checkbox" 
                               class="ingredient-checkbox w-5 h-5 text-blue-600 rounded cursor-pointer" 
                               checked 
                               data-ingredient-id="${detection.ingredient_id}"
                               onchange="updateConfirmedIngredients(this)">
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-lg">${detection.ingredient_name}</p>
                            ${detectedNameInfo}
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="confidence-bar h-full rounded-full" style="width: ${confidencePercent}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 min-w-[45px]">${confidencePercent}%</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-white"></i>
                    </div>
                </div>
            `;
            container.appendChild(item);
        });

        // Initialize confirmed ingredients
        updateConfirmedIngredients();
        document.getElementById('detectionResults').style.display = 'block';
        
        // Update step indicator
        updateStepIndicator(2);
    }
    
    // Update step indicator
    function updateStepIndicator(step) {
        for (let i = 1; i <= 4; i++) {
            const stepEl = document.getElementById(`step${i}`);
            if (stepEl) {
                if (i <= step) {
                    stepEl.classList.add('active');
                } else {
                    stepEl.classList.remove('active');
                }
            }
        }
    }

    // Update confirmed ingredient IDs
    function updateConfirmedIngredients(checkbox) {
        const checkboxes = document.querySelectorAll('.ingredient-checkbox:checked');
        confirmedIngredientIds = Array.from(checkboxes).map(cb => parseInt(cb.dataset.ingredientId));
        
        // Update visual state
        if (checkbox) {
            const item = checkbox.closest('.detection-item');
            if (checkbox.checked) {
                item.classList.add('checked');
            } else {
                item.classList.remove('checked');
            }
        }
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
                updateStepIndicator(3);
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
            updateStepIndicator(4);
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

        suggestions.forEach((suggestion, index) => {
            const item = document.createElement('div');
            item.className = 'dish-card p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200';
            item.style.animationDelay = `${index * 0.1}s`;
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

