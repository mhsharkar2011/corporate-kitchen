{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            @if (auth()->user()->role === 'admin')
                {{-- Admin Dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Total Orders</p>
                                <p class="text-2xl font-bold">{{ $totalOrders ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Pending Orders</p>
                                <p class="text-2xl font-bold">{{ $pendingOrders ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Today's Orders</p>
                                <p class="text-2xl font-bold">{{ $todayOrders ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Revenue</p>
                                <p class="text-2xl font-bold">৳{{ number_format($revenue ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold">Recent Orders</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentOrders ?? [] as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            ৳{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full
                                                @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($order->status == 'delivered') bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No orders found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                {{-- User Dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-center">
                            <p class="text-gray-500">Total Orders</p>
                            <p class="text-3xl font-bold text-orange-600">{{ $totalOrders ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-center">
                            <p class="text-gray-500">Active Orders</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $activeOrders ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-center">
                            <p class="text-gray-500">Completed Orders</p>
                            <p class="text-3xl font-bold text-green-600">{{ $completedOrders ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Your Recent Orders</h3>
                        <a href="{{ route('orders.create') }}"
                            class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
                            Place New Order
                        </a>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentOrders ?? [] as $order)
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold">Order #{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                        <p class="text-sm mt-1">Items: {{ $order->items->count() }}</p>
                                        <p class="font-bold text-orange-600 mt-1">
                                            ৳{{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="px-3 py-1 text-sm rounded-full
                                            @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'preparing') bg-purple-100 text-purple-800
                                            @elseif($order->status == 'delivered') bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <div class="mt-2">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="text-orange-600 hover:text-orange-700 text-sm">
                                                View Details →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500">
                                <p>You haven't placed any orders yet.</p>
                                <a href="{{ route('orders.create') }}"
                                    class="inline-block mt-4 text-orange-600 hover:text-orange-700">
                                    Start Shopping →
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
