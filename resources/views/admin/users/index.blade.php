{{-- resources/views/admin/users/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Corporate Kitchen Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation (same as layout) -->
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
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow p-4">
                        <p class="text-xs text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg shadow p-4">
                        <p class="text-xs text-blue-600">Admins</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $stats['admin'] }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg shadow p-4">
                        <p class="text-xs text-purple-600">Suppliers</p>
                        <p class="text-2xl font-bold text-purple-700">{{ $stats['supplier'] }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg shadow p-4">
                        <p class="text-xs text-yellow-600">Supervisors</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $stats['supervisor'] }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg shadow p-4">
                        <p class="text-xs text-green-600">Clients</p>
                        <p class="text-2xl font-bold text-green-700">{{ $stats['client'] }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg shadow p-4">
                        <p class="text-xs text-orange-600">Active Today</p>
                        <p class="text-2xl font-bold text-orange-700">{{ $stats['active_today'] }}</p>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">User Management</h2>
                            <a href="{{ route('admin.users.create') }}"
                                class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                                + Add New User
                            </a>
                        </div>

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

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Joined</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $user->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full
                                                    @if ($user->role == 'admin') bg-red-100 text-red-800
                                                    @elseif($user->role == 'supplier') bg-purple-100 text-purple-800
                                                    @elseif($user->role == 'supervisor') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->phone ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($user->is_active)
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                    class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
