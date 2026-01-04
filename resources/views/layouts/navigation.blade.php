<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex">
                @auth
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                  
                </div>
                @endauth
            </div>

            <!-- RIGHT SIDE -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

          

                {{-- GUEST LINKS --}}
                @guest
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Register
                    </a>
                </div>
                @endguest

            </div>

            <!-- HAMBURGER -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400
                           hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                            class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                            class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- RESPONSIVE MENU -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        @auth
        <!-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div> -->

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

   
        </div>
        @endauth

        @guest
        <div class="pt-4 pb-3 border-t border-gray-200 space-y-2 px-4">
            <a href="{{ route('login') }}" class="block text-gray-600">Login</a>
            <a href="{{ route('register') }}" class="block text-gray-600">Register</a>
        </div>
        @endguest

    </div>
</nav>
