<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Products') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap 4 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white fixed inset-y-0 left-0">
        <div class="px-6 py-4 text-lg font-bold border-b border-gray-700">
            Admin Panel
        </div>

        <nav class="mt-4 space-y-1 text-gray-300">
            <a href="/admin/products"
               class="block px-6 py-3 hover:bg-gray-800">
                Products
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
        <main class="p-6">
            <!-- Page Content -->
        </main>
    </div>

</div>
</body>
</html>
