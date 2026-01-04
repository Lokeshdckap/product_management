<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category = new Category();
        $category->uuid = (string) Str::uuid();
        $category->name = $validator->validated()['name'];
        $category->save();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->name = $validator->validated()['name'];
        $category->save();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Category has products. Cannot delete.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
