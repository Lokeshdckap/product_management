
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h2 class="text-3xl font-bold text-white">Edit Product</h2>
                <p class="text-blue-100 mt-2">Update product information</p>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="px-8 py-6">
                @csrf
                @method('PUT')

                {{-- Name Field --}}
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $product->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror" 
                        placeholder="Enter product name"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none @error('description') border-red-500 @enderror"
                        placeholder="Describe your product..."
                    >{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    
                    {{-- Price Field --}}
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-500 font-medium">$</span>
                            <input 
                                type="number" 
                                name="price" 
                                id="price" 
                                value="{{ old('price', $product->price) }}"
                                step="0.01"
                                min="0"
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('price') border-red-500 @enderror" 
                                placeholder="0.00"
                            >
                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                            Stock Quantity <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="stock" 
                            id="stock" 
                            value="{{ old('stock', $product->stock) }}"
                            min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('stock') border-red-500 @enderror" 
                            placeholder="0"
                        >
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="category_id" 
                        id="category_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('category_id') border-red-500 @enderror"
                    >
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Image
                    </label>
                    @if($product->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-48 rounded-lg shadow-md">
                    </div>
                    @endif

                    <div class="flex items-center justify-center w-full">
                        <label for="image" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition @error('image') border-red-500 @enderror">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload new image</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG or WEBP (MAX. 2MB)</p>
                            </div>
                            <input 
                                type="file" 
                                name="image" 
                                id="image" 
                                class="hidden" 
                                accept="image/*"
                                onchange="previewImage(event)"
                            >
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                        <img src="" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                    </div>
                    
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-lg">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = preview.querySelector('img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection