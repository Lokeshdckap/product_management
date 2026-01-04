@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800">Product Management</h1>
    
    <div class="flex gap-3">
        <a href="{{ url('/admin/categories/create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
            Create New Category
        </a>
        <a href="{{ url('/admin/products/create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            Create New Product
        </a>
        <button
        onclick="openImportModal()"
        class="px-4 py-2 bg-black text-white text-sm font-medium rounded-md hover:bg-gray-800 transition-colors">
        Import CSV File
    </button>
    </div>
</div>

<!-- Import CSV Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Import Products (CSV)</h2>
            <button onclick="closeImportModal()" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        </div>

        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Choose CSV File
                </label>
                <input
                    type="file"
                    name="file"
                    accept=".csv,.xlsx"
                    required
                    class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeImportModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Import Products
                </button>
            </div>
        </form>
    </div>
</div>


    <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Product Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Stock</th>

                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
    @foreach($products as $product)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 text-sm text-gray-700">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover">
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ $product->name }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ $product->description }}</td>
            <td>{{ $product->category?->name ?? 'Uncategorized' }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ $product->stock }}</td>
            <td class="px-6 py-4 text-right text-sm">
                <a class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"  href="{{ route('admin.products.edit', $product) }}">Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to delete this product?')"
                            class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
        </table>
    </div>

</div>
@endsection

<script>
    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
        document.getElementById('importModal').classList.add('flex');
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
        document.getElementById('importModal').classList.remove('flex');
    }
</script>
