<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Management | Corporate Kitchen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow p-4">
                        <p class="text-xs text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg shadow p-4 border-l-4 border-yellow-500">
                        <p class="text-xs text-yellow-600">Pending</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg shadow p-4 border-l-4 border-blue-500">
                        <p class="text-xs text-blue-600">Confirmed</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $stats['confirmed'] }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <p class="text-xs text-purple-600">Preparing</p>
                        <p class="text-2xl font-bold text-purple-700">{{ $stats['preparing'] }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg shadow p-4 border-l-4 border-green-500">
                        <p class="text-xs text-green-600">Delivered</p>
                        <p class="text-2xl font-bold text-green-700">{{ $stats['delivered'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg shadow p-4 border-l-4 border-gray-500">
                        <p class="text-xs text-gray-600">Closed</p>
                        <p class="text-2xl font-bold text-gray-700">{{ $stats['closed'] }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg shadow p-4">
                        <p class="text-xs text-gray-500">Revenue</p>
                        <p class="text-lg font-bold text-green-600">৳{{ number_format($stats['revenue'], 2) }}</p>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-6">All Orders</h2>

                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Order #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Delivery Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($orders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="text-sm font-medium text-gray-900">{{ $order->order_number }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="text-sm font-semibold text-gray-900">৳{{ number_format($order->total_amount, 2) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full
                                                    @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'preparing') bg-purple-100 text-purple-800
                                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                                    @elseif($order->status == 'closed') bg-gray-100 text-gray-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                                No orders found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
