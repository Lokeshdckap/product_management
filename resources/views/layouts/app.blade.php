<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Products') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white fixed inset-y-0 left-0 flex flex-col">
        <div class="px-6 py-4 text-lg font-bold border-b border-gray-700">
            Admin Panel
        </div>

        <nav class="mt-4 flex-1 px-2 space-y-1 text-gray-300">
            <a href="{{ url('/admin/dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-800 transition-colors">
               Dashboard
            </a>
            <a href="{{ url('/admin/categories') }}"
               class="block px-4 py-2 rounded hover:bg-gray-800 transition-colors">
               Category
            </a>
            <a href="{{ url('/admin/products') }}"
               class="block px-4 py-2 rounded hover:bg-gray-800 transition-colors">
               Products
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-gray-700">
            <a href=""
               class="block px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-center transition-colors">
                Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
        <main class="p-6">
            @yield('content')
        </main>
    </div>

</div>

</body>
</html>
