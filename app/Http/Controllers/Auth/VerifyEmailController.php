<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Xác minh email qua link ký số, không yêu cầu đang đăng nhập
     */
    public function __invoke(Request $request, string $id, string $hash): RedirectResponse
    {
        // Lấy user theo id từ link
        $user = User::find($id);

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Liên kết xác minh không hợp lệ hoặc người dùng không tồn tại.',
            ]);
        }

        // Kiểm tra hash khớp theo chuẩn Laravel: sha1(email)
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors([
                'email' => 'Liên kết xác minh không hợp lệ hoặc đã bị thay đổi.',
            ]);
        }

        // Nếu đã xác minh trước đó
        if ($user->hasVerifiedEmail()) {
            // đăng nhập luôn để trải nghiệm mượt
            Auth::login($user);
            return redirect()->intended(route('home').'?verified=1');
        }

        // Cập nhật cột email_verified_at và bắn sự kiện
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Tự đăng nhập sau khi xác minh thành công
        Auth::login($user);

        return redirect()->intended(route('home').'?verified=1');
    }
}
