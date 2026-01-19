@extends('layouts.customer')

@section('title', 'Demo Test Gemini API')

@section('styles')
<style>
    .test-card {
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }
    .test-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    .response-box {
        background: #f8f9fa;
        border-left: 4px solid #0d6efd;
        padding: 1rem;
        border-radius: 4px;
        white-space: pre-wrap;
        word-wrap: break-word;
        max-height: 400px;
        overflow-y: auto;
    }
    .api-key-status {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-weight: 500;
    }
    .api-key-status.has-key {
        background: #d1e7dd;
        color: #0f5132;
    }
    .api-key-status.no-key {
        background: #f8d7da;
        color: #842029;
    }
    .loading-spinner {
        display: none;
    }
    .loading-spinner.active {
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold mb-3">
                <i class="fas fa-vial text-primary me-2"></i>
                Demo Test Gemini API
            </h1>
            <p class="lead text-muted">Trang này giúp bạn kiểm tra xem GEMINI_API_KEY có hoạt động đúng không</p>
        </div>
    </div>

    <!-- API Key Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card test-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-key me-2"></i>
                        Trạng thái API Key
                    </h5>
                    <div class="api-key-status {{ $hasApiKey ? 'has-key' : 'no-key' }} mt-3">
                        @if($hasApiKey)
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>API Key đã được cấu hình:</strong> {{ $apiKeyPreview }}
                        @else
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>API Key chưa được cấu hình!</strong> Vui lòng thêm GEMINI_API_KEY vào file .env
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Test với Text -->
        <div class="col-lg-6 mb-4">
            <div class="card test-card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comment-dots me-2"></i>
                        Test với Text Prompt
                    </h5>
                </div>
                <div class="card-body">
                    <form id="textTestForm">
                        @csrf
                        <div class="mb-3">
                            <label for="textModel" class="form-label">Chọn Model:</label>
                            <select class="form-select" id="textModel" required>
                                <option value="gemini-2.5-flash" selected>gemini-2.5-flash (Mặc định)</option>
                                <option value="gemini-2.5-pro">gemini-2.5-pro</option>
                                <option value="gemini-1.5-flash">gemini-1.5-flash</option>
                                <option value="gemini-1.5-pro">gemini-1.5-pro</option>
                                <option value="gemini-pro">gemini-pro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="textPrompt" class="form-label">Nhập prompt (câu hỏi hoặc yêu cầu):</label>
                            <textarea 
                                class="form-control" 
                                id="textPrompt" 
                                rows="4" 
                                placeholder="Ví dụ: Hãy kể cho tôi về ẩm thực Việt Nam bằng tiếng Việt"
                                required
                            ></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="textTestBtn">
                            <span class="loading-spinner spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            <i class="fas fa-paper-plane me-2"></i>
                            Gửi Request
                        </button>
                    </form>

                    <div id="textResponse" class="mt-4" style="display: none;">
                        <h6 class="fw-bold mb-2">Kết quả:</h6>
                        <div class="response-box" id="textResponseContent"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test với Image -->
        <div class="col-lg-6 mb-4">
            <div class="card test-card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-image me-2"></i>
                        Test với Hình ảnh
                    </h5>
                </div>
                <div class="card-body">
                    <form id="imageTestForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="imageModel" class="form-label">Chọn Model:</label>
                            <select class="form-select" id="imageModel" required>
                                <option value="gemini-2.5-flash" selected>gemini-2.5-flash (Mặc định)</option>
                                <option value="gemini-2.5-pro">gemini-2.5-pro</option>
                                <option value="gemini-1.5-flash">gemini-1.5-flash</option>
                                <option value="gemini-1.5-pro">gemini-1.5-pro</option>
                                <option value="gemini-pro">gemini-pro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="imageFile" class="form-label">Chọn hình ảnh:</label>
                            <input 
                                type="file" 
                                class="form-control" 
                                id="imageFile" 
                                accept="image/jpeg,image/jpg,image/png"
                                required
                            >
                            <small class="text-muted">Hỗ trợ: JPEG, JPG, PNG (tối đa 10MB)</small>
                        </div>
                        <div class="mb-3">
                            <label for="imagePrompt" class="form-label">Prompt (tùy chọn):</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="imagePrompt" 
                                placeholder="Ví dụ: Mô tả chi tiết hình ảnh này bằng tiếng Việt"
                            >
                            <small class="text-muted">Để trống sẽ sử dụng prompt mặc định</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="imageTestBtn">
                            <span class="loading-spinner spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            <i class="fas fa-upload me-2"></i>
                            Upload & Test
                        </button>
                    </form>

                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <h6 class="fw-bold mb-2">Hình ảnh đã chọn:</h6>
                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>

                    <div id="imageResponse" class="mt-4" style="display: none;">
                        <h6 class="fw-bold mb-2">Kết quả:</h6>
                        <div class="response-box" id="imageResponseContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Test với Text
    document.getElementById('textTestForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const prompt = document.getElementById('textPrompt').value;
        const btn = document.getElementById('textTestBtn');
        const spinner = btn.querySelector('.loading-spinner');
        const responseDiv = document.getElementById('textResponse');
        const responseContent = document.getElementById('textResponseContent');

        // Disable button và show loading
        btn.disabled = true;
        spinner.classList.add('active');
        responseDiv.style.display = 'none';

        try {
            const response = await fetch('{{ route("api.test.gemini.text") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    prompt: prompt,
                    model: document.getElementById('textModel').value
                })
            });

            const data = await response.json();

            if (data.success) {
                responseContent.textContent = data.data.response;
                responseContent.style.borderLeftColor = '#198754';
                responseDiv.style.display = 'block';
            } else {
                responseContent.textContent = 'Lỗi: ' + data.message + '\n\nChi tiết:\n' + JSON.stringify(data, null, 2);
                responseContent.style.borderLeftColor = '#dc3545';
                responseDiv.style.display = 'block';
            }
        } catch (error) {
            responseContent.textContent = 'Lỗi kết nối: ' + error.message;
            responseContent.style.borderLeftColor = '#dc3545';
            responseDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
            spinner.classList.remove('active');
        }
    });

    // Preview image
    document.getElementById('imageFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Test với Image
    document.getElementById('imageTestForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('imageFile');
        const prompt = document.getElementById('imagePrompt').value;
        const btn = document.getElementById('imageTestBtn');
        const spinner = btn.querySelector('.loading-spinner');
        const responseDiv = document.getElementById('imageResponse');
        const responseContent = document.getElementById('imageResponseContent');

        if (!fileInput.files[0]) {
            alert('Vui lòng chọn một hình ảnh');
            return;
        }

        // Disable button và show loading
        btn.disabled = true;
        spinner.classList.add('active');
        responseDiv.style.display = 'none';

        try {
            const formData = new FormData();
            formData.append('image', fileInput.files[0]);
            formData.append('model', document.getElementById('imageModel').value);
            if (prompt) {
                formData.append('prompt', prompt);
            }

            const response = await fetch('{{ route("api.test.gemini.image") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                responseContent.textContent = data.data.response;
                responseContent.style.borderLeftColor = '#198754';
                responseDiv.style.display = 'block';
            } else {
                responseContent.textContent = 'Lỗi: ' + data.message + '\n\nChi tiết:\n' + JSON.stringify(data, null, 2);
                responseContent.style.borderLeftColor = '#dc3545';
                responseDiv.style.display = 'block';
            }
        } catch (error) {
            responseContent.textContent = 'Lỗi kết nối: ' + error.message;
            responseContent.style.borderLeftColor = '#dc3545';
            responseDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
            spinner.classList.remove('active');
        }
    });
</script>
@endpush

