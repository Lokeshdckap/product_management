<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Customer Home</title>

    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/js/app.js'])
</head>

<body class="bg-gray-100">

<nav class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
    <div class="text-xl font-bold text-blue-600">MyShop</div>

    <div class="relative">
        <button id="userMenuBtn" class="flex items-center gap-2">
            <span class="text-gray-700 font-medium">
                {{ auth('customer')->user()->name }}
            </span>
        </button>

        <div id="userMenu"
             class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-8">
    <h2 class="text-2xl font-bold mb-6">Products</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <div class="bg-white rounded-lg shadow">
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="w-full h-48 object-cover rounded-t-lg">

                <div class="p-4">
                    <h3 class="font-semibold">{{ $product->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $product->description }}</p>
                    <span class="text-green-600 font-bold">₹{{ number_format($product->price) }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
</div>

<script>
document.getElementById('userMenuBtn').addEventListener('click', () => {
    document.getElementById('userMenu').classList.toggle('hidden');
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo) {
        console.error('Echo not loaded');
        return;
    }

    axios.post('/presence/customer-joined')
        .then(response => {
            console.log('Customer joined notification :', response.data);
        })
        .catch(error => {
            console.log('Failed error:', error);
        });

    Echo.join('presence-customers')
        .here(users => {
            console.log('Customers currently online:', users);
        })
        .joining(user => {
            broadcastCustomerJoined();
        })
        .leaving(user => {
            broadcastCustomerLeft();
        });

    window.addEventListener('beforeunload', () => {
        axios.post('/presence/customer-left').catch(error => {
            console.log("left");
        });
    });

    function broadcastCustomerJoined() {
        axios.post('/presence/customer-joined')
            .then(response => {
                console.log('Joininggg');
            })
            .catch(error => {
                console.log('Failed:', error);
            });
    }

    function broadcastCustomerLeft() {
        axios.post('/presence/customer-left')
            .then(response => {
                console.log('Left 1');
            })
            .catch(error => {
                console.log(' left 2:', error);
            });
    }

});
</script>

</body>
</html>
