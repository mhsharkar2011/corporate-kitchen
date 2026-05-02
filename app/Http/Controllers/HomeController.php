<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_available', true)->get();

        // Get today's cutoff time
        $cutoffTime = Carbon::today()->setTime(21, 0, 0);
        $canOrderToday = Carbon::now()->lt($cutoffTime);

        // Get active orders for authenticated user
        $activeOrders = auth()->check()
            ? Order::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed', 'preparing'])
            ->with('items.menu')
            ->latest()
            ->get()
            : collect();

        return view('home', compact('menus', 'canOrderToday', 'activeOrders'));
    }
}
