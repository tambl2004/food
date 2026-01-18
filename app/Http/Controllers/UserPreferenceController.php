<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPreferenceController extends Controller
{
    /**
     * Display the user's preferences page.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        
        // Lấy hoặc tạo preferences mặc định
        $preferences = $user->userPreference ?? UserPreference::create([
            'user_id' => $user->id,
            'favorite_categories' => [],
            'diet_type' => null,
            'spicy_level' => 0,
            'disliked_ingredients' => [],
            'health_goal' => null,
        ]);

        return view('customer.preferences.index', [
            'preferences' => $preferences,
        ]);
    }

    /**
     * Update the user's preferences.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'favorite_categories' => ['nullable', 'array'],
            'favorite_categories.*' => ['string', 'max:255'],
            'diet_type' => ['nullable', 'string', 'max:255'],
            'spicy_level' => ['required', 'integer', 'min:0', 'max:3'],
            'disliked_ingredients' => ['nullable', 'array'],
            'disliked_ingredients.*' => ['string', 'max:255'],
            'health_goal' => ['nullable', 'string', 'max:255'],
        ]);

        // Update hoặc create preferences
        $preferences = $user->userPreference;

        if ($preferences) {
            $preferences->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            UserPreference::create($validated);
        }

        return redirect()->route('preferences.show')
            ->with('success', 'Cập nhật sở thích thành công!');
    }

    /**
     * Reset preferences to default values.
     */
    public function reset(Request $request): RedirectResponse
    {
        $user = $request->user();
        $preferences = $user->userPreference;

        if ($preferences) {
            $preferences->update([
                'favorite_categories' => [],
                'diet_type' => null,
                'spicy_level' => 0,
                'disliked_ingredients' => [],
                'health_goal' => null,
            ]);
        }

        return redirect()->route('preferences.show')
            ->with('success', 'Đã đặt lại sở thích về mặc định!');
    }
}
