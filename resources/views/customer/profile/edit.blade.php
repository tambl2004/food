@extends('layouts.customer')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-2">Hồ sơ cá nhân</h1>
        <p class="text-muted">Quản lý thông tin tài khoản của bạn</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Profile Info Card -->
        <div class="col-lg-4 mb-4">
            <div class="customer-card">
                <div class="customer-card-header">
                    <h3 class="customer-card-title">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Thông tin cơ bản
                    </h3>
                </div>
                <div class="customer-card-body text-center">
                    <!-- Avatar Display -->
                    <div class="mb-4">
                        @if($user->avatar)
                            <img src="{{ asset($user->avatar) }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle border border-3 border-primary"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle border border-3 border-primary d-inline-flex align-items-center justify-content-center bg-primary text-white"
                                 style="width: 120px; height: 120px; font-size: 3rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>

                    <!-- User Information -->
                    <h4 class="fw-bold mb-2">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        {{ $user->email }}
                    </p>
                    <p class="text-muted small">
                        <i class="fas fa-calendar me-2"></i>
                        Tham gia: {{ $user->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column - Edit Forms -->
        <div class="col-lg-8">
            <!-- Update Profile Form -->
            <div class="customer-card mb-4">
                <div class="customer-card-header">
                    <h3 class="customer-card-title">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Chỉnh sửa thông tin
                    </h3>
                </div>
                <div class="customer-card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Họ và tên <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Avatar Upload -->
                        <div class="mb-4">
                            <label for="avatar" class="form-label fw-semibold">Ảnh đại diện</label>
                            <input type="file" 
                                   class="form-control @error('avatar') is-invalid @enderror" 
                                   id="avatar" 
                                   name="avatar" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <small class="form-text text-muted">
                                Chấp nhận: JPG, PNG, GIF (tối đa 2MB)
                            </small>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview Avatar -->
                            <div id="avatar-preview" class="mt-3" style="display: none;">
                                <img id="avatar-preview-img" 
                                     src="" 
                                     alt="Preview" 
                                     class="rounded border"
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Lưu thay đổi
                            </button>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="customer-card">
                <div class="customer-card-header">
                    <h3 class="customer-card-title">
                        <i class="fas fa-key me-2 text-warning"></i>
                        Đổi mật khẩu
                    </h3>
                </div>
                <div class="customer-card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">
                                Mật khẩu hiện tại <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                Mật khẩu mới <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   minlength="6">
                            <small class="form-text text-muted">
                                Mật khẩu tối thiểu 6 ký tự
                            </small>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                Xác nhận mật khẩu mới <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   minlength="6">
                        </div>

                        <!-- Form Actions -->
                        <div>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>
                                Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview functionality
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarPreviewImg = document.getElementById('avatar-preview-img');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreviewImg.src = e.target.result;
                    avatarPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                avatarPreview.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
@endsection

