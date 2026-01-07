<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Customer Home</title>

    <!-- Tailwind CDN (OK for now) -->
    <script src="https://cdn.tailwindcss.com"></script>
        <script>
    window.Laravel = {
        userType: '{{ auth("admin")->check() ? "admin" : (auth("customer")->check() ? "customer" : "") }}'
    };
</script>
  @vite(['resources/js/app.js'])
    @stack('scripts')
</head>
<body class="bg-gray-100">

<!-- NAVBAR -->
<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <!-- Logo -->
    <div class="text-xl font-bold text-blue-600">
        MyShop
    </div>

    <!-- User Dropdown -->
    <div class="relative">
        <button id="userMenuBtn" class="flex items-center gap-2 focus:outline-none">
            <span class="text-gray-700 font-medium">
                {{ auth('customer')->user()->name }}
            </span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Dropdown -->
        <div id="userMenu"
             class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- PRODUCTS -->
<div class="max-w-7xl mx-auto px-6 py-8">
    <h2 class="text-2xl font-bold mb-6">Products</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                
                <!-- Image -->
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="w-full h-48 object-cover rounded-t-lg">

                <!-- Content -->
                <div class="p-4">
                    <h3 class="font-semibold text-lg truncate">
                        {{ $product->name }}
                    </h3>

                    <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                        {{ $product->description }}
                    </p>

                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-lg font-bold text-green-600">
                            ₹{{ number_format($product->price) }}
                        </span>

                        <button
                            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    const btn = document.getElementById('userMenuBtn');
    const menu = document.getElementById('userMenu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
    document.addEventListener('DOMContentLoaded', () => {
    Echo.join('presence-customers');
});
</script>
<!-- STACKED SCRIPTS MUST BE AT THE END OF BODY -->
@stack('scripts')
</body>
</html>
