<nav x-data="{ open: false, searchOpen: false }" class="bg-white/90 backdrop-blur border-b border-blue-500">
    @php
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        $homeRoute = auth()->check() ? route('dashboard') : route('home');
    @endphp
    <!-- Primary Navigation Menu -->
    <div class="w-full max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 relative overflow-visible">
            <div class="flex items-center">

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    @if ($isAdmin)
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Manage Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                            {{ __('Manage Products') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            {{ __('Manage Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                            {{ __('Manage Orders') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.analytics.sales')" :active="request()->routeIs('admin.analytics.*')">
                            {{ __('Sales Analytics') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.ratings.index')" :active="request()->routeIs('admin.ratings.*')">
                            {{ __('Manage Reviews') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="auth()->check() ? route('dashboard') : route('home')" :active="request()->routeIs('dashboard') || request()->routeIs('home')">
                            {{ __('HOME') }}
                        </x-nav-link>
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                            {{ __('PRODUCTS') }}
                        </x-nav-link>
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index') || request()->routeIs('categories.products')">
                            {{ __('CATEGORIES') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            @if (! $isAdmin)
                <!-- Centered Logo -->
                <div class="absolute inset-x-0 hidden sm:flex justify-center pointer-events-none">
                    <div class="h-16 flex items-center pointer-events-auto">
                        <a href="{{ $homeRoute }}" class="flex flex-col items-center leading-tight">
                            <span class="tracking-[0.3em] text-[10px] text-blue-500 font-medium">FLEUR DE PEAU</span>
                            <span class="text-xs text-gray-700">Skincare Store</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                @if (! $isAdmin)
                    <div class="flex items-center gap-2" @click.outside="searchOpen = false">
                        <form x-show="searchOpen"
                              x-transition
                              method="GET"
                              action="{{ route('products.index') }}"
                              class="w-64"
                              data-live-search-form>
                            <input x-ref="navSearchInput"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="I'm looking for..."
                                   data-live-search
                                   class="w-full border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </form>

                        <button type="button"
                                @click="searchOpen = !searchOpen; if (searchOpen) { $nextTick(() => $refs.navSearchInput?.focus()); }"
                                class="inline-flex items-center p-2 text-gray-500 hover:text-blue-500 transition"
                                aria-label="Search products"
                                title="Search products">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                @endif
                @auth
                    @if (! $isAdmin)
                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center p-2 text-gray-500 hover:text-blue-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @php
                                $cartCount = auth()->user()->carts()->first()?->items()->count() ?? 0;
                            @endphp
                            @if ($cartCount > 0)
                                <span class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-blue-500" style="border-radius: 50%;">{{ $cartCount }}</span>
                            @endif
                        </a>

                            <!-- Favorites Icon -->
                            <a href="{{ route('favorites.index') }}" class="inline-flex items-center p-2 text-gray-500 hover:text-pink-500 transition" title="Favorites">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                </svg>
                            </a>

                        <!-- Orders Icon -->
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center p-2 text-gray-500 hover:text-blue-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center h-10 w-10 border border-slate-200 bg-white hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150" style="border-radius: 50% !important;" aria-label="Account" title="Account">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="h-10 w-10 object-cover" style="border-radius: 50% !important;" alt="{{ Auth::user()->name }}">
                        @else
                            <span class="h-10 w-10 bg-gray-200 text-gray-700 text-sm font-semibold flex items-center justify-center" style="border-radius: 50% !important;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-sm font-medium text-gray-700 hover:bg-slate-50 hover:text-blue-500" style="border-radius: 0.375rem !important;">LOG IN</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-500" style="border-radius: 0.375rem !important;">REGISTER</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if ($isAdmin)
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Home') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Manage Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        {{ __('Manage Products') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Manage Categories') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        {{ __('Manage Orders') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.analytics.sales')" :active="request()->routeIs('admin.analytics.*')">
                        {{ __('Sales Analytics') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.ratings.index')" :active="request()->routeIs('admin.ratings.*')">
                        {{ __('Manage Reviews') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Products') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index') || request()->routeIs('categories.products')">
                        {{ __('Categories') }}
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    {{ __('Products') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index') || request()->routeIs('categories.products')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4 flex items-center gap-3">
                    @if (Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="h-10 w-10 rounded-full object-cover" alt="">
                    @endif
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Account') }}
                    </x-responsive-nav-link>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
