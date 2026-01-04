@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800">Category Management</h1>
    
    <div class="flex gap-3">
        <a href="{{ url('/admin/categories/create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
            Create New Category
        </a>
        <a href="{{ url('/admin/products/create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            Create New Product
        </a>
    </div>
</div>

    <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">S-no</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Category Name</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
    @foreach($categories as $index => $category)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 text-sm text-gray-900">{{ $index +1 }}</td>

            <td class="px-6 py-4 text-sm text-gray-900">{{ $category->name }}</td>
            <td class="px-6 py-4 text-right text-sm">
                <a class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"  href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to delete this Category ?')"
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