{{-- resources/views/orders/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Kitchen - My Orders</title>
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
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-orange-600">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">My Orders</h2>
                        <a href="{{ route('orders.create') }}"
                            class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                            + New Order
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($orders->count() > 0)
                        <div class="space-y-4">
                            @foreach ($orders as $order)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <p class="font-semibold text-lg">Order #{{ $order->order_number }}</p>
                                                <span
                                                    class="px-3 py-1 rounded-full text-sm font-semibold
                                                    @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'preparing') bg-purple-100 text-purple-800
                                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-2">
                                                {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                            <p class="text-sm mt-2">Delivery:
                                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                                            </p>
                                            <p class="text-sm">Items: {{ $order->items->count() }}</p>
                                            <p class="font-bold text-orange-600 mt-2">
                                                ৳{{ number_format($order->total_amount, 2) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="text-orange-600 hover:text-orange-700 inline-block">
                                                View Details →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No orders</h3>
                            <p class="mt-1 text-sm text-gray-500">You haven't placed any orders yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('orders.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                    Place Your First Order
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
