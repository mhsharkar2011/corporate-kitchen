{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Kitchen - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-orange-600">Corporate Kitchen</a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            @php
                                $userRole = auth()->user()->role ?? 'client';
                            @endphp

                            <!-- Common Links for All Roles -->
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-700 hover:text-orange-600 transition">Dashboard</a>

                            <!-- Role-Based Navigation -->
                            @switch($userRole)
                                @case('admin')
                                    <!-- Admin Navigation -->
                                    <div class="relative group">
                                        <button class="text-gray-700 hover:text-orange-600 transition flex items-center">
                                            Management
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div
                                            class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-2 py-2 w-48 z-50">
                                            <a href="{{ route('admin.menus.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Menu
                                                Management</a>
                                            <a href="{{ route('admin.orders.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Order
                                                Management</a>
                                            <a href="{{ route('admin.users.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">User
                                                Management</a>
                                            <a href="{{ route('admin.reports.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Reports</a>
                                            <a href="{{ route('admin.settings.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Settings</a>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.analytics') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Analytics</a>
                                    <a href="{{ route('admin.suppliers.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Suppliers</a>
                                    <a href="{{ route('admin.staff.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Staff</a>
                                @break

                                @case('supplier')
                                    <!-- Supplier Navigation -->
                                    <a href="{{ route('supplier.orders.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Orders</a>
                                    <a href="{{ route('supplier.inventory.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Inventory</a>
                                    <a href="{{ route('supplier.deliveries.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Deliveries</a>
                                    <a href="{{ route('supplier.products.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Products</a>
                                    <a href="{{ route('supplier.payments.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Payments</a>
                                @break

                                @case('supervisor')
                                    <!-- Supervisor Navigation -->
                                    <a href="{{ route('supervisor.orders.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">All Orders</a>
                                    <a href="{{ route('supervisor.kitchen.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Kitchen Status</a>
                                    <a href="{{ route('supervisor.staff.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Staff Management</a>
                                    <a href="{{ route('supervisor.inventory.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Inventory</a>
                                    <a href="{{ route('supervisor.quality.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Quality Control</a>
                                @break

                                @default
                                    <!-- Client/Normal User Navigation -->
                                    <a href="{{ route('orders.create') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Order Now</a>
                                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-orange-600 transition">My
                                        Orders</a>
                                    <a href="{{ route('user.subscriptions.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Subscriptions</a>
                                    <a href="{{ route('user.wishlist.index') }}"
                                        class="text-gray-700 hover:text-orange-600 transition">Wishlist</a>
                                    <div class="relative group">
                                        <button class="text-gray-700 hover:text-orange-600 transition flex items-center">
                                            My Account
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div
                                            class="absolute hidden group-hover:block bg-white shadow-lg rounded-md mt-2 py-2 w-48 z-50">
                                            <a href="{{ route('profile.edit') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">My
                                                Profile</a>
                                            <a href="{{ route('user.addresses.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Addresses</a>
                                            <a href="{{ route('user.payments.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Payment
                                                Methods</a>
                                            <a href="{{ route('user.rewards.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">Rewards</a>
                                        </div>
                                    </div>
                                @break
                            @endswitch

                            <!-- User Profile Dropdown -->
                            <div class="relative group ml-4">
                                <button class="flex items-center space-x-2 focus:outline-none">
                                    <div
                                        class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div
                                    class="absolute right-0 hidden group-hover:block bg-white shadow-lg rounded-md mt-2 py-2 w-48 z-50">
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">
                                        <i class="fas fa-user mr-2"></i> My Profile
                                    </a>
                                    <a href="{{ route('profile.settings') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600">
                                        <i class="fas fa-cog mr-2"></i> Settings
                                    </a>
                                    <div class="border-t my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-orange-600 transition">Login</a>
                            <a href="{{ route('register') }}"
                                class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">Register</a>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    @auth
                        @php
                            $userRole = auth()->user()->role ?? 'client';
                        @endphp

                        <a href="{{ route('dashboard') }}"
                            class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Dashboard</a>

                        @switch($userRole)
                            @case('admin')
                                <a href="{{ route('admin.menus.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Menu
                                    Management</a>
                                <a href="{{ route('admin.orders.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Order
                                    Management</a>
                                <a href="{{ route('admin.users.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">User
                                    Management</a>
                                <a href="{{ route('admin.reports.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Reports</a>
                                <a href="{{ route('admin.suppliers.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Suppliers</a>
                            @break

                            @case('supplier')
                                <a href="{{ route('supplier.orders.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Orders</a>
                                <a href="{{ route('supplier.inventory.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Inventory</a>
                                <a href="{{ route('supplier.deliveries.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Deliveries</a>
                            @break

                            @case('supervisor')
                                <a href="{{ route('supervisor.orders.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">All
                                    Orders</a>
                                <a href="{{ route('supervisor.kitchen.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Kitchen
                                    Status</a>
                                <a href="{{ route('supervisor.staff.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Staff
                                    Management</a>
                            @break

                            @default
                                <a href="{{ route('orders.create') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Order
                                    Now</a>
                                <a href="{{ route('orders.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">My
                                    Orders</a>
                                <a href="{{ route('user.subscriptions.index') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">Subscriptions</a>
                            @break
                        @endswitch

                        <div class="border-t my-2"></div>
                        <a href="{{ route('profile.edit') }}"
                            class="block px-3 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-md">My
                            Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left block px-3 py-2 text-red-600 hover:bg-red-50 rounded-md">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in">
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div
                class="fixed top-20 right-4 z-50 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('info'))
            <div
                class="fixed top-20 right-4 z-50 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in">
                {{ session('info') }}
            </div>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Corporate Kitchen. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.fixed.top-20.right-4').forEach(message => {
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 300);
            });
        }, 5000);
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>

</html>
