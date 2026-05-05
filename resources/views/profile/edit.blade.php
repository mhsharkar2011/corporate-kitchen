{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">My Profile</h2>
                        <div class="flex space-x-3">
                            <a href="{{ route('profile.settings') }}"
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                                Settings
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Avatar Section -->
                        <div class="text-center">
                            <div class="mb-4">
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
                            </div>

                            <form action="{{ route('profile.avatar.update') }}" method="POST"
                                enctype="multipart/form-data" id="avatar-form">
                                @csrf
                                @method('PATCH')
                                <label
                                    class="cursor-pointer bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 inline-block">
                                    Change Avatar
                                    <input type="file" name="avatar" accept="image/*" class="hidden"
                                        onchange="document.getElementById('avatar-form').submit()">
                                </label>
                            </form>
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Information Form -->
                        <div class="md:col-span-2">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                    <textarea name="address" rows="3"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700">
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
