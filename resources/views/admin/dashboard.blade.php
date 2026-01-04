@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <a href="{{ url('/admin/products/create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
            Create New Product
        </a>
    </div>

    <!-- Product Table -->
    <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Product Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900">Sample Product</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <img src="https://via.placeholder.com/50" alt="Product Image" class="w-12 h-12 rounded">
                    </td>
                    <td class="px-6 py-4 text-right text-sm">
                        <a href="#"
                           class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                           Edit
                        </a>
                        <a href="#"
                           class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors ml-2">
                           Delete
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
