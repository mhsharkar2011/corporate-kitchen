{{-- resources/views/orders/create.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Kitchen - Place Order</title>
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
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-orange-600">My Orders</a>
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
                    <h2 class="text-2xl font-bold mb-6">Place Your Order</h2>

                    <!-- Flash Messages -->
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST" id="order-form">
                        @csrf

                        <!-- Menu Items Selection -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Select Items</h3>
                            <div class="grid md:grid-cols-2 gap-4" id="menu-items-container">
                                @forelse($menus as $index => $menu)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-lg">{{ $menu->name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ Str::limit($menu->description, 100) }}</p>
                                                <p class="text-orange-600 font-bold mt-2">
                                                    ৳{{ number_format($menu->price, 2) }}</p>
                                                <p class="text-xs text-gray-500 mt-1">Category:
                                                    {{ ucfirst($menu->category) }}</p>
                                            </div>
                                            <div class="ml-4">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                                <div class="flex items-center space-x-2">
                                                    <button type="button"
                                                        class="decrement-btn w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300 focus:outline-none">-</button>
                                                    <input type="number" name="items[{{ $index }}][quantity]"
                                                        class="quantity-input w-20 text-center border rounded-lg py-1"
                                                        value="0" min="0" max="99"
                                                        data-price="{{ $menu->price }}"
                                                        data-menu-id="{{ $menu->id }}">
                                                    <input type="hidden" name="items[{{ $index }}][menu_id]"
                                                        value="{{ $menu->id }}">
                                                    <button type="button"
                                                        class="increment-btn w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300 focus:outline-none">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 text-center py-8">
                                        <p class="text-gray-500">No menu items available at the moment.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Delivery Information -->
                        <div class="mb-6 border-t pt-6">
                            <h3 class="text-lg font-semibold mb-4">Delivery Information</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Date *</label>
                                    <input type="date" name="delivery_date" required
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    @error('delivery_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address
                                        *</label>
                                    <textarea name="delivery_address" rows="3" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                        placeholder="Enter your complete delivery address"></textarea>
                                    @error('delivery_address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Special Instructions -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions
                                (Optional)</label>
                            <textarea name="special_instructions" rows="2"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Any allergies, preferred delivery time, etc."></textarea>
                        </div>

                        <!-- Order Summary -->
                        <div class="border-t pt-6 bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-semibold">Total Amount:</span>
                                <span class="text-2xl font-bold text-orange-600" id="total-amount">৳0</span>
                            </div>
                            <button type="submit"
                                class="w-full bg-orange-600 text-white py-3 rounded-lg font-semibold hover:bg-orange-700 transition">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const totalAmountSpan = document.getElementById('total-amount');

            function calculateTotal() {
                let total = 0;
                quantityInputs.forEach(input => {
                    const quantity = parseInt(input.value) || 0;
                    const price = parseFloat(input.dataset.price);
                    total += quantity * price;
                });
                totalAmountSpan.textContent = '৳' + total.toFixed(2);
            }

            // Add event listeners to each quantity input
            quantityInputs.forEach(input => {
                input.addEventListener('change', calculateTotal);
                input.addEventListener('keyup', calculateTotal);

                // Find the container for this input
                const container = input.closest('.border');
                if (container) {
                    const decrementBtn = container.querySelector('.decrement-btn');
                    const incrementBtn = container.querySelector('.increment-btn');

                    if (decrementBtn) {
                        decrementBtn.addEventListener('click', () => {
                            let value = parseInt(input.value);
                            if (value > 0) {
                                input.value = value - 1;
                                calculateTotal();
                            }
                        });
                    }

                    if (incrementBtn) {
                        incrementBtn.addEventListener('click', () => {
                            let value = parseInt(input.value);
                            if (value < 99) {
                                input.value = value + 1;
                                calculateTotal();
                            }
                        });
                    }
                }
            });

            // Initial calculation
            calculateTotal();
        });
    </script>
</body>

</html>
