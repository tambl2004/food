<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google OAuth provider
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if ($user) {
                // User exists with Google ID, login directly
                Auth::login($user);
                return $this->redirectAfterLogin($user);
            }
            
            // Check if user exists with same email
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Link Google account to existing user
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $existingUser->email_verified_at ?? Carbon::now(),
                ]);
                
                Auth::login($existingUser);
                return $this->redirectAfterLogin($existingUser);
            }
            
            // Create new user with Google OAuth
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => Carbon::now(), // Auto-verify for Google users
                'password' => Hash::make(uniqid()), // Generate random password
                'role' => UserRole::USER, // Default role
            ]);
            
            Auth::login($newUser);
            return $this->redirectAfterLogin($newUser);
            
        } catch (\Exception $e) {
            // Handle errors gracefully
            return redirect()->route('login')->withErrors([
                'email' => 'Đã xảy ra lỗi khi đăng nhập bằng Google. Vui lòng thử lại.',
            ]);
        }
    }

    /**
     * Redirect user after successful login based on role
     */
    private function redirectAfterLogin(User $user)
    {
        if ($user->role === UserRole::ADMIN) {
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        }
        
        // Khách hàng: kiểm tra nếu chưa có preferences thì điều hướng đến onboarding
        if (!$user->userPreference) {
            return redirect()->route('onboarding.preferences');
        }
        
        return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
    }
}