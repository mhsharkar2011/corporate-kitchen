<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display all orders for admin
     */
    public function index()
    {
        $orders = Order::with('user', 'items.menu')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'closed' => Order::where('status', 'closed')->count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show specific order details
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.menu');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Confirm order
     */
    public function confirm(Order $order)
    {
        if (!$order->canBeConfirmed()) {
            return redirect()->back()->with('error', 'This order cannot be confirmed.');
        }

        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been confirmed!');
    }

    /**
     * Start preparing order
     */
    public function startPreparing(Order $order)
    {
        if ($order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Order must be confirmed first.');
        }

        $order->update(['status' => 'preparing']);

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' is now being prepared.');
    }

    /**
     * Mark order as delivered
     */
    public function deliver(Order $order)
    {
        if (!$order->canBeDelivered()) {
            return redirect()->back()->with('error', 'This order cannot be marked as delivered.');
        }

        $order->update([
            'status' => 'delivered',
            'delivered_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been marked as delivered!');
    }

    /**
     * Close order after delivery
     */
    public function close(Order $order)
    {
        if (!$order->canBeClosed()) {
            return redirect()->back()->with('error', 'Order must be delivered first before closing.');
        }

        $order->update([
            'status' => 'closed',
            'closed_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been closed.');
    }

    /**
     * Edit order items (only for confirmed orders before delivery)
     */
    public function edit(Order $order)
    {
        if (!$order->canBeEdited()) {
            return redirect()->back()->with('error', 'This order cannot be edited.');
        }

        $menus = Menu::where('is_available', true)->get();
        return view('admin.orders.edit', compact('order', 'menus'));
    }

    /**
     * Update order items
     */
    public function update(Request $request, Order $order)
    {
        if (!$order->canBeEdited()) {
            return redirect()->back()->with('error', 'This order cannot be edited.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        // Delete existing items
        $order->items()->delete();

        // Add new items
        $totalAmount = 0;
        foreach ($request->items as $item) {
            if ($item['quantity'] > 0) {
                $menu = Menu::find($item['menu_id']);
                $subtotal = $menu->price * $item['quantity'];
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $menu->price,
                    'subtotal' => $subtotal,
                ]);
            }
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully!');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if ($order->status !== 'pending' && $order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' has been cancelled.');
    }
}
