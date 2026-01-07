@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800">Imports Details</h1>
    
</div>

    <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Import Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Original File </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">processed_rows</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">failed_rows</th>


                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions(Download Failed File)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
    @foreach($imports as $import)
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900">
            {{ ucfirst($import->import_type) }}
        </td>

        <td class="px-6 py-4 text-sm text-gray-900">
            {{ basename($import->original_file) }}
        </td>

        <td class="px-6 py-4 text-sm text-gray-900">
            <span class="
                px-2 py-1 rounded text-xs font-semibold
                @if($import->status === 'completed') bg-green-100 text-green-800
                @elseif($import->status === 'completed_with_errors') bg-yellow-100 text-yellow-800
                @elseif($import->status === 'processing') bg-blue-100 text-blue-800
                @else bg-gray-100 text-gray-800
                @endif
            ">
                {{ str_replace('_', ' ', ucfirst($import->status)) }}
            </span>
        </td>

        <td class="px-6 py-4 text-sm text-gray-900">{{ $import->processed_rows }}</td>
        <td class="px-6 py-4 text-sm text-gray-900">{{ $import->failed_rows }}</td>

        <td class="px-6 py-4 text-right text-sm">
            @if($import->failed_rows > 0 && $import->failed_file)
                <a href="{{ route('admin.imports.failed.download', $import) }}"
                   class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                    Download Failed
                </a>
            @else
                <span class="text-gray-400">—</span>
            @endif
        </td>
    </tr>
@endforeach

</tbody>
        </table>
    </div>

</div>
@endsection