<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - Corporate Kitchen Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-orange-600">Corporate Kitchen Admin</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-orange-600">Dashboard</a>
                        <a href="{{ route('admin.menus.index') }}" class="text-gray-700 hover:text-orange-600">Menu</a>
                        <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-orange-600">Orders</a>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-orange-600">Users</a>
                        <a href="{{ route('admin.analytics') }}" class="text-orange-600 font-semibold">Analytics</a>
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
                <!-- Revenue Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Today's Revenue</p>
                                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($revenueData['today'], 2) }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">This Week</p>
                                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($revenueData['week'], 2) }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">This Month</p>
                                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($revenueData['month'], 2) }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        @if($revenueData['growth'] != 0)
                            <p class="text-xs mt-2 {{ $revenueData['growth'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $revenueData['growth'] > 0 ? '↑' : '↓' }} {{ abs($revenueData['growth']) }}% from last month
                            </p>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Average Order Value</p>
                                <p class="text-2xl font-bold text-gray-800">৳{{ number_format($revenueData['average_order_value'], 2) }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-full">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Orders Overview</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Today's Orders</p>
                                <p class="text-2xl font-bold">{{ $orderData['today'] }}</p>
                                <p class="text-xs text-green-600">{{ $orderData['today_completed'] }} completed</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">This Week</p>
                                <p class="text-2xl font-bold">{{ $orderData['week'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">This Month</p>
                                <p class="text-2xl font-bold">{{ $orderData['month'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Delivery Success Rate</p>
                                <p class="text-2xl font-bold">{{ $orderData['delivery_success_rate'] }}%</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Order Status Breakdown</h3>
                        <div class="space-y-3">
                            @foreach($orderData['status_breakdown'] as $status => $count)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="capitalize">{{ $status }}</span>
                                        <span>{{ $count }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $total = array_sum($orderData['status_breakdown']);
                                            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                                        @endphp
                                        <div class="h-2 rounded-full
                                            @if($status == 'pending') bg-yellow-500
                                            @elseif($status == 'confirmed') bg-blue-500
                                            @elseif($status == 'preparing') bg-purple-500
                                            @elseif($status == 'delivered') bg-green-500
                                            @elseif($status == 'closed') bg-gray-500
                                            @else bg-red-500
                                            @endif"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Daily Sales Chart -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Daily Sales (Last 7 Days)</h3>
                    <canvas id="salesChart" height="100"></canvas>
                </div>

                <!-- Popular Items & Category Performance -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Top 10 Popular Items</h3>
                        <div class="space-y-4">
                            @foreach($popularItems as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">{{ $item->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->category }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold">{{ $item->total_quantity }} sold</p>
                                        <p class="text-sm text-gray-500">৳{{ number_format($item->total_revenue, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Category Performance</h3>
                        <canvas id="categoryChart" height="200"></canvas>
                    </div>
                </div>

                <!-- Customer Analytics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Customer Insights</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Customers</span>
                                <span class="font-semibold">{{ $customerData['total'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">New Customers (This Month)</span>
                                <span class="font-semibold">{{ $customerData['new_this_month'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Active Customers (30 days)</span>
                                <span class="font-semibold">{{ $customerData['active'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Repeat Customer Rate</span>
                                <span class="font-semibold">{{ $customerData['repeat_rate'] }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Top Customers</h3>
                        <div class="space-y-3">
                            @foreach($customerData['top_customers'] as $customer)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">{{ $customer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                                    </div>
                                    <p class="font-semibold">৳{{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Peak Hours -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Peak Ordering Hours</h3>
                    <canvas id="peakHoursChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Daily Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($dailySales)->pluck('day')) !!},
                datasets: [
                    {
                        label: 'Orders',
                        data: {!! json_encode(collect($dailySales)->pluck('orders')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        yAxisID: 'y',
                    },
                    {
                        label: 'Revenue (৳)',
                        data: {!! json_encode(collect($dailySales)->pluck('revenue')) !!},
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    },
                    y1: {
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Revenue (৳)'
                        }
                    }
                }
            }
        });

        // Category Performance Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryPerformance->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($categoryPerformance->pluck('total_revenue')) !!},
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(249, 115, 22)'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Peak Hours Chart
        const peakCtx = document.getElementById('peakHoursChart').getContext('2d');
        new Chart(peakCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($peakHours->pluck('hour')->map(function($hour) {
                    return date('g A', strtotime("$hour:00"));
                })) !!},
                datasets: [{
                    label: 'Orders',
                    data: {!! json_encode($peakHours->pluck('total_orders')) !!},
                    backgroundColor: 'rgb(249, 115, 22)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
