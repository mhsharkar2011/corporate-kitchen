<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    // Remove the constructor with middleware
    // In Laravel 11, middleware is defined in routes instead

    public function create()
    {
        $menus = Menu::where('is_available', true)->get();
        return view('orders.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_address' => 'required|string|min:10',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                // Skip items with quantity 0
                if ($item['quantity'] <= 0) {
                    continue;
                }

                $menu = Menu::findOrFail($item['menu_id']);
                $subtotal = $menu->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $menu->price,
                    'subtotal' => $subtotal,
                ];
            }

            if (empty($orderItems)) {
                return back()->with('error', 'Please select at least one item.');
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'delivery_date' => $request->delivery_date,
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
            ]);

            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function show(Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $order->load('items.menu');
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        // For admin, show all orders; for regular users, show only their orders
        if (auth()->user()->role === 'admin') {
            $orders = Order::with('user', 'items.menu')->latest()->paginate(10);
        } else {
            $orders = Order::where('user_id', auth()->id())
                ->with('items.menu')
                ->latest()
                ->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }

    // Additional method to cancel order if needed
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Order cancelled successfully.');
        }

        return redirect()->back()->with('error', 'This order cannot be cancelled.');
    }
}
