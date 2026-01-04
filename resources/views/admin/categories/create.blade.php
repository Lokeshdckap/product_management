
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h2 class="text-3xl font-bold text-white">Create New Category</h2>
                <p class="text-blue-100 mt-2">Add a new Category to your inventory</p>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="px-8 py-6">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror" 
                        placeholder="Enter Category name"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-lg">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection