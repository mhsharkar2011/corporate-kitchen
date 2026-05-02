<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Kitchen - Order Details</title>
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
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-orange-600">Dashboard</a>
                    <a href="{{ route('orders.create') }}" class="text-gray-700 hover:text-orange-600">Order Now</a>
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-orange-600">My Orders</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-orange-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Order #{{ $order->order_number }}</h2>
                            <p class="text-gray-600">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div
                            class="px-4 py-2 rounded-full text-sm font-semibold
                            @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status == 'preparing') bg-purple-100 text-purple-800
                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </div>
                    </div>

                    <!-- Order Status Timeline -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            @php
                                $statuses = ['pending', 'confirmed', 'preparing', 'delivered'];
                                $currentStatusIndex = array_search($order->status, $statuses);
                            @endphp
                            @foreach ($statuses as $index => $status)
                                <div class="flex-1 text-center">
                                    <div class="relative">
                                        @if ($index <= $currentStatusIndex)
                                            <div
                                                class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center text-white text-sm mx-auto">
                                                {{ $index + 1 }}
                                            </div>
                                        @else
                                            <div
                                                class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-sm mx-auto">
                                                {{ $index + 1 }}
                                            </div>
                                        @endif
                                        <span class="text-xs mt-2 block capitalize">{{ $status }}</span>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <div class="flex-1">
                                        <div class="h-1 bg-gray-200">
                                            <div class="h-full bg-orange-600 transition-all duration-500"
                                                style="width: {{ $index < $currentStatusIndex ? '100%' : '0%' }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border-t pt-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="space-y-3">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between items-center py-2">
                                    <div>
                                        <p class="font-medium">{{ $item->quantity }}x {{ $item->menu->name }}</p>
                                        <p class="text-sm text-gray-500">৳{{ number_format($item->unit_price, 2) }}
                                            each</p>
                                    </div>
                                    <p class="font-semibold">৳{{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t mt-4 pt-4">
                            <div class="flex justify-between items-center">
                                <p class="font-bold text-lg">Total</p>
                                <p class="font-bold text-xl text-orange-600">
                                    ৳{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">Delivery Information</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Delivery Date</p>
                                <p class="font-medium">
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Delivery Address</p>
                                <p class="font-medium">{{ $order->delivery_address }}</p>
                            </div>
                            @if ($order->special_instructions)
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600">Special Instructions</p>
                                    <p class="font-medium">{{ $order->special_instructions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('orders.index') }}"
                            class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                            Back to Orders
                        </a>
                        @if ($order->status == 'pending')
                            <form method="POST" action="{{ route('orders.cancel', $order) }}"
                                onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
