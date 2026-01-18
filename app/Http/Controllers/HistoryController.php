<?php

namespace App\Http\Controllers;

use App\Models\UserFoodHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display the user's food history page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $actionType = $request->get('action', 'ALL'); // ALL, VIEWED, SAVED, COOKED

        // Query lịch sử với relationships
        $query = UserFoodHistory::where('user_id', $user->id)
            ->with(['dish.category'])
            ->orderBy('action_at', 'desc');

        // Filter theo action_type nếu không phải ALL
        if ($actionType !== 'ALL') {
            $query->where('action', strtolower($actionType));
        }

        // Paginate
        $histories = $query->paginate(12)->withQueryString();

        return view('customer.history.index', [
            'histories' => $histories,
            'actionType' => $actionType,
        ]);
    }

    /**
     * API endpoint to get user food history (JSON response).
     */
    public function getHistoryApi(Request $request)
    {
        $user = Auth::user();
        $actionType = $request->get('action', 'ALL');

        $query = UserFoodHistory::where('user_id', $user->id)
            ->with(['dish.category'])
            ->orderBy('action_at', 'desc');

        if ($actionType !== 'ALL') {
            $query->where('action', strtolower($actionType));
        }

        $histories = $query->paginate(12);

        // Format data theo shape yêu cầu
        $formattedHistories = $histories->map(function ($history) {
            return [
                'history_id' => $history->id,
                'dish_id' => $history->dish_id,
                'dish_name' => $history->dish->name ?? 'N/A',
                'dish_image' => $history->dish->image ? asset('storage/' . $history->dish->image) : null,
                'category_name' => $history->dish->category->name ?? 'N/A',
                'action_type' => strtoupper($history->action),
                'created_at' => $history->action_at->format('Y-m-d H:i:s'),
                'created_at_human' => $history->action_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedHistories,
            'pagination' => [
                'current_page' => $histories->currentPage(),
                'last_page' => $histories->lastPage(),
                'per_page' => $histories->perPage(),
                'total' => $histories->total(),
            ],
        ]);
    }
}
