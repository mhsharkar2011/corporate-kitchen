<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (auth()->user()->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        // Get statistics for admin
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $revenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');

        // Get recent orders
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // Get menu items count
        $totalMenuItems = Menu::count();
        $availableItems = Menu::where('is_available', true)->count();

        return view('dashboard', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'todayOrders' => $todayOrders,
            'revenue' => $revenue,
            'recentOrders' => $recentOrders,
            'totalMenuItems' => $totalMenuItems,
            'availableItems' => $availableItems,
            'canOrderToday' => $this->checkCanOrderToday(),
        ]);
    }

    private function userDashboard()
    {
        $user = auth()->user();

        // Get user's orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with('items.menu')
            ->latest()
            ->take(5)
            ->get();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $completedOrders = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->count();

        $activeOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->count();

        return view('dashboard', [
            'recentOrders' => $recentOrders,
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'activeOrders' => $activeOrders,
            'canOrderToday' => $this->checkCanOrderToday(),
        ]);
    }

    private function checkCanOrderToday()
    {
        $cutoffTime = Carbon::today()->setTime(21, 0, 0);
        return Carbon::now()->lt($cutoffTime);
    }
}
