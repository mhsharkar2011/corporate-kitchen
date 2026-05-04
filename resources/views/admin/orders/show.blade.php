<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Details | Corporate Kitchen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-orange-600">Corporate Kitchen
                            Admin</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-orange-600">Dashboard</a>
                        <a href="{{ route('admin.menus.index') }}" class="text-gray-700 hover:text-orange-600">Menu</a>
                        <a href="{{ route('admin.orders.index') }}" class="text-orange-600 font-semibold">Orders</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-orange-600">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="py-12">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-bold">Order #{{ $order->order_number }}</h2>
                                <p class="text-gray-600">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
                                <p class="text-gray-600">Customer: {{ $order->user->name }} ({{ $order->user->email }})
                                </p>
                            </div>
                            <div class="text-right">
                                <div
                                    class="px-4 py-2 rounded-full text-sm font-semibold inline-block
                                    @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'preparing') bg-purple-100 text-purple-800
                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status == 'closed') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </div>
                            </div>
                        </div>

                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Order Timeline -->
                        <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Order Timeline</h3>
                            <div class="relative">
                                <div class="flex justify-between">
                                    <div class="text-center flex-1">
                                        <div
                                            class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                            {{ $order->created_at ? 'bg-green-500 text-white' : 'bg-gray-300' }}">
                                            ✓
                                        </div>
                                        <p class="text-xs mt-2 font-semibold">Order Placed</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->created_at ? $order->created_at->format('M d, h:i A') : '-' }}
                                        </p>
                                    </div>
                                    <div class="text-center flex-1">
                                        <div
                                            class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                            {{ $order->confirmed_at ? 'bg-blue-500 text-white' : 'bg-gray-300' }}">
                                            {{ $order->confirmed_at ? '✓' : '1' }}
                                        </div>
                                        <p class="text-xs mt-2 font-semibold">Confirmed</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->confirmed_at ? $order->confirmed_at->format('M d, h:i A') : 'Pending' }}
                                        </p>
                                    </div>
                                    <div class="text-center flex-1">
                                        <div
                                            class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                            {{ $order->status == 'preparing' || $order->delivered_at ? 'bg-purple-500 text-white' : 'bg-gray-300' }}">
                                            {{ $order->status == 'preparing' || $order->delivered_at ? '✓' : '2' }}
                                        </div>
                                        <p class="text-xs mt-2 font-semibold">Preparing</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->status == 'preparing' ? 'In Progress' : ($order->delivered_at ? 'Completed' : 'Pending') }}
                                        </p>
                                    </div>
                                    <div class="text-center flex-1">
                                        <div
                                            class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                            {{ $order->delivered_at ? 'bg-green-500 text-white' : 'bg-gray-300' }}">
                                            {{ $order->delivered_at ? '✓' : '3' }}
                                        </div>
                                        <p class="text-xs mt-2 font-semibold">Delivered</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->delivered_at ? $order->delivered_at->format('M d, h:i A') : 'Pending' }}
                                        </p>
                                    </div>
                                    <div class="text-center flex-1">
                                        <div
                                            class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                            {{ $order->closed_at ? 'bg-gray-500 text-white' : 'bg-gray-300' }}">
                                            {{ $order->closed_at ? '✓' : '4' }}
                                        </div>
                                        <p class="text-xs mt-2 font-semibold">Closed</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->closed_at ? $order->closed_at->format('M d, h:i A') : 'Pending' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="border-t pt-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                            <div class="space-y-3">
                                @foreach ($order->items as $item)
                                    <div class="flex justify-between items-center py-2 border-b">
                                        <div>
                                            <p class="font-medium">{{ $item->quantity }}x {{ $item->menu->name }}</p>
                                            <p class="text-sm text-gray-500">৳{{ number_format($item->unit_price, 2) }}
                                                each</p>
                                        </div>
                                        <p class="font-semibold">৳{{ number_format($item->subtotal, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <div class="flex justify-between items-center">
                                    <p class="font-bold text-lg">Total</p>
                                    <p class="font-bold text-xl text-orange-600">
                                        ৳{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Information -->
                        <div class="border-t pt-6 mb-6">
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

                        <!-- Action Buttons -->
                        <div class="border-t pt-6">
                            <div class="flex flex-wrap gap-3">
                                @if ($order->canBeConfirmed())
                                    <form method="POST" action="{{ route('admin.orders.confirm', $order) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                            Confirm Order
                                        </button>
                                    </form>
                                @endif

                                @if ($order->status == 'confirmed')
                                    <form method="POST" action="{{ route('admin.orders.prepare', $order) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                                            Start Preparing
                                        </button>
                                    </form>
                                @endif

                                @if ($order->status == 'preparing')
                                    <form method="POST" action="{{ route('admin.orders.deliver', $order) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                                            Mark as Delivered
                                        </button>
                                    </form>
                                @endif

                                @if ($order->canBeClosed())
                                    <form method="POST" action="{{ route('admin.orders.close', $order) }}"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to close this order?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                                            Close Order
                                        </button>
                                    </form>
                                @endif

                                @if ($order->canBeEdited())
                                    <a href="{{ route('admin.orders.edit', $order) }}"
                                        class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700">
                                        Edit Order
                                    </a>
                                @endif

                                @if ($order->status == 'pending' || $order->status == 'confirmed')
                                    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.orders.index') }}"
                                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                                    Back to Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
