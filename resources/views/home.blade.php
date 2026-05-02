<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Kitchen - Home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-orange-600">Corporate Kitchen</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('orders.create') }}" class="text-gray-700 hover:text-orange-600">Order Now</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-orange-600">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-orange-600">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl shadow-xl mb-12 overflow-hidden">
                <div class="px-8 py-12 text-center text-white">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Corporate Kitchen</h1>
                    <p class="text-xl mb-6">Fresh, Delicious Meals Delivered to Your Doorstep</p>
                    @guest
                        <a href="{{ route('register') }}"
                            class="inline-block bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                            Get Started
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Cutoff Time Banner -->
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8 rounded">
                <p class="font-semibold">
                    @if (isset($canOrderToday) && $canOrderToday)
                        ⏰ Order before 9:00 PM for next day delivery!
                    @else
                        ⏰ Today's cutoff time has passed. Orders placed now will be delivered the day after tomorrow.
                    @endif
                </p>
            </div>

            <!-- Active Orders Section for Logged-in Users -->
            @auth
                @if (isset($activeOrders) && $activeOrders->count() > 0)
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold mb-6">Your Active Orders</h2>
                        <div class="grid gap-6">
                            @foreach ($activeOrders as $order)
                                <div class="bg-white rounded-lg shadow-md p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold">Order #{{ $order->order_number }}</h3>
                                            <p class="text-gray-600">Delivery:
                                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</p>
                                        </div>
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'preparing') bg-purple-100 text-purple-800
                                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div class="border-t pt-4">
                                        <p class="font-semibold">Items:</p>
                                        @foreach ($order->items as $item)
                                            <p class="text-gray-600">{{ $item->quantity }}x {{ $item->menu->name }} -
                                                ৳{{ $item->subtotal }}</p>
                                        @endforeach
                                        <p class="font-semibold mt-2">Total: ৳{{ $order->total_amount }}</p>
                                    </div>
                                    <a href="{{ route('orders.show', $order) }}"
                                        class="inline-block mt-4 text-orange-600 hover:text-orange-700">
                                        View Details →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Menu Section -->
            <div>
                <h2 class="text-3xl font-bold text-center mb-8">Today's Menu</h2>
                @if (isset($menus) && $menus->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($menus as $menu)
                            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                                @if ($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No image</span>
                                    </div>
                                @endif
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-xl font-bold">{{ $menu->name }}</h3>
                                        <span
                                            class="text-orange-600 font-bold">৳{{ number_format($menu->price, 2) }}</span>
                                    </div>
                                    <p class="text-gray-600 mb-4">{{ Str::limit($menu->description, 100) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">{{ ucfirst($menu->category) }}</span>
                                        @auth
                                            <a href="{{ route('orders.create') }}?menu={{ $menu->id }}"
                                                class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                                                Order Now
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}"
                                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                                                Login to Order
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500">No menu items available at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
