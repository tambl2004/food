<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FavoriteDish;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = User::latest('created_at');

        // Tìm kiếm theo tên hoặc email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Lọc theo vai trò
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);

        // Thống kê
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $blockedUsers = User::where('status', 'blocked')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'blockedUsers'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng mới thành công!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thông tin người dùng thành công!');
    }

    /**
     * Hiển thị chi tiết người dùng
     */
    public function show(User $user)
    {
        // Tính toán thống kê
        $statistics = [
            'favorite_count' => FavoriteDish::where('user_id', $user->id)->count(),
            'review_count' => Review::where('user_id', $user->id)->count(),
            // Note: viewed_count, cooked_count, scan_count chưa có trong database
            // Có thể thêm sau nếu cần
            'viewed_count' => 0,
            'cooked_count' => 0,
            'scan_count' => 0,
        ];

        return view('admin.users.show', compact('user', 'statistics'));
    }

    /**
     * Cập nhật trạng thái user (block/unblock)
     */
    public function updateStatus(Request $request, User $user)
    {
        // Ngăn admin tự khóa chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể khóa chính mình!');
        }

        $validated = $request->validate([
            'status' => 'required|in:active,blocked',
        ]);

        $user->update(['status' => $validated['status']]);

        $message = $validated['status'] === 'active' 
            ? 'Đã mở khóa tài khoản thành công!' 
            : 'Đã khóa tài khoản thành công!';

        return back()->with('success', $message);
    }

    public function destroy(User $user)
    {
        // Ngăn admin tự xóa chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể xóa chính mình!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng thành công!');
    }
}