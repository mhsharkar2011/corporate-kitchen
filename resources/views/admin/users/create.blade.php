{{-- resources/views/admin/users/create.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Corporate Kitchen Admin</title>
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
                        <a href="{{ url('/') }}" class="text-xl font-bold text-orange-600">Corporate Kitchen Admin</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-orange-600">Dashboard</a>
                        <a href="{{ route('admin.menus.index') }}" class="text-gray-700 hover:text-orange-600">Menu</a>
                        <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-orange-600">Orders</a>
                        <a href="{{ route('admin.users.index') }}" class="text-orange-600 font-semibold">Users</a>
                        <a href="{{ route('admin.analytics') }}" class="text-gray-700 hover:text-orange-600">Analytics</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-orange-600">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Create New User</h2>
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                Back to Users
                            </a>
                        </div>

                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                                <div class="font-medium mb-2">Please fix the following errors:</div>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                    <input type="password"
                                           name="password"
                                           required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                                <select name="role"
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                                    <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea name="address"
                                          rows="3"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-600">Account is active</span>
                                </label>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.users.index') }}"
                                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                                    Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
