<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the ingredients.
     */
    public function index(Request $request)
    {
        $q = $request->input('q');
        $query = \App\Models\Ingredient::orderBy('name');
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
                    ->orWhere('unit', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $ingredients = $query->paginate(10)->appends(['q' => $q]);
        return view('admin.ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new ingredient.
     */
    public function create()
    {
        return view('admin.ingredients.create');
    }

    /**
     * Store a newly created ingredient.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = \Str::slug($data['name']);

        \App\Models\Ingredient::create($data);

        return redirect()->route('admin.ingredients.index')->with('success', 'Thêm nguyên liệu thành công.');
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit($id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified ingredient.
     */
    public function update(Request $request, $id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = \Str::slug($data['name']);

        $ingredient->update($data);

        return redirect()->route('admin.ingredients.index')->with('success', 'Cập nhật nguyên liệu thành công.');
    }

    /**
     * Remove the specified ingredient.
     */
    public function destroy($id)
    {
        $ingredient = \App\Models\Ingredient::find($id);
        if ($ingredient) {
            $ingredient->delete();
            return redirect()->route('admin.ingredients.index')->with('success', 'Xóa nguyên liệu thành công.');
        }
        return redirect()->route('admin.ingredients.index')->with('error', 'Nguyên liệu không tìm thấy.');
    }
}
