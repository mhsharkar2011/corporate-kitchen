<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Account Settings</h2>
                        <a href="{{ route('profile.edit') }}"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            Back to Profile
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-8">
                        <!-- Change Password Section -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                            <form action="{{ route('profile.password.update') }}" method="POST" class="max-w-md">
                                @csrf
                                @method('PATCH')

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <input type="password" name="current_password" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="password" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New
                                        Password</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                </div>

                                <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                    Update Password
                                </button>
                            </form>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-semibold mb-4">Notification Preferences</h3>
                            <form action="{{ route('profile.notifications.update') }}" method="POST">
                                @csrf
                                @method('PATCH')

                                @php
                                    $prefs = json_decode($user->notification_preferences ?? '{}', true);
                                @endphp

                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_notifications" value="1"
                                            {{ isset($prefs['email_notifications']) && $prefs['email_notifications'] ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-orange-600">
                                        <span class="ml-2 text-gray-700">Email Notifications</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="checkbox" name="sms_notifications" value="1"
                                            {{ isset($prefs['sms_notifications']) && $prefs['sms_notifications'] ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-orange-600">
                                        <span class="ml-2 text-gray-700">SMS Notifications</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="checkbox" name="order_updates" value="1"
                                            {{ isset($prefs['order_updates']) && $prefs['order_updates'] ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-orange-600">
                                        <span class="ml-2 text-gray-700">Order Updates</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="checkbox" name="promotional_emails" value="1"
                                            {{ isset($prefs['promotional_emails']) && $prefs['promotional_emails'] ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-orange-600">
                                        <span class="ml-2 text-gray-700">Promotional Emails</span>
                                    </label>
                                </div>

                                <div class="mt-4">
                                    <button type="submit"
                                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                                        Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Delete Account Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-red-600 mb-4">Delete Account</h3>
                            <p class="text-sm text-gray-600 mb-4">Once you delete your account, there is no going back.
                                All your data will be permanently removed.</p>

                            <form action="{{ route('profile.destroy') }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')

                                <div class="mb-4 max-w-md">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                    <input type="password" name="password" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                </div>

                                <button type="submit"
                                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                                    Delete My Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
