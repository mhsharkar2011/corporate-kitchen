{{-- resources/views/admin/users/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - Corporate Kitchen Admin</title>
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
                        <a href="{{ route('admin.orders.index') }}"
                            class="text-gray-700 hover:text-orange-600">Orders</a>
                        <a href="{{ route('admin.users.index') }}" class="text-orange-600 font-semibold">Users</a>
                        <a href="{{ route('admin.analytics') }}"
                            class="text-gray-700 hover:text-orange-600">Analytics</a>
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
                            <h2 class="text-2xl font-bold">User Details</h2>
                            <div class="space-x-3">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                    Edit User
                                </a>
                                <a href="{{ route('admin.users.index') }}"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                    Back to Users
                                </a>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="grid md:grid-cols-3 gap-6 mb-8">
                            <div class="text-center">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        class="w-32 h-32 rounded-full mx-auto object-cover">
                                @else
                                    <div
                                        class="w-32 h-32 bg-orange-600 rounded-full mx-auto flex items-center justify-center">
                                        <span class="text-4xl font-bold text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <h3 class="mt-4 text-xl font-semibold">{{ $user->name }}</h3>
                                <p class="text-gray-500">{{ ucfirst($user->role) }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-medium">{{ $user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="font-medium">{{ $user->phone ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-sm text-gray-500">Address</p>
                                        <p class="font-medium">{{ $user->address ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Status</p>
                                        @if ($user->is_active)
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Member Since</p>
                                        <p class="font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Statistics -->
                        <div class="border-t pt-6 mb-8">
                            <h3 class="text-lg font-semibold mb-4">Order Statistics</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Total Orders</p>
                                    <p class="text-2xl font-bold">{{ $orderStats['total'] }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Total Spent</p>
                                    <p class="text-2xl font-bold text-green-600">
                                        ৳{{ number_format($orderStats['total_spent'], 2) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Completed Orders</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $orderStats['completed'] }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-500">Pending Orders</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ $orderStats['pending'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders -->
                        @if ($user->orders && $user->orders->count() > 0)
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Order #</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Amount</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Status</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($user->orders->take(5) as $order)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        {{ $order->order_number }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        {{ $order->created_at->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
