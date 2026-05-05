<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index()
    {
        // Date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Revenue Analytics
        $revenueData = $this->getRevenueAnalytics($today, $thisWeek, $thisMonth, $lastMonth);

        // Order Analytics
        $orderData = $this->getOrderAnalytics($today, $thisWeek, $thisMonth);

        // Popular Items
        $popularItems = $this->getPopularItems();

        // Customer Analytics
        $customerData = $this->getCustomerAnalytics($today, $thisWeek, $thisMonth);

        // Daily Sales for Chart
        $dailySales = $this->getDailySales();

        // Category Performance
        $categoryPerformance = $this->getCategoryPerformance();

        // Peak Hours
        $peakHours = $this->getPeakHours();

        return view('admin.analytics.index', compact(
            'revenueData',
            'orderData',
            'popularItems',
            'customerData',
            'dailySales',
            'categoryPerformance',
            'peakHours'
        ));
    }

    /**
     * Get revenue analytics.
     */
    private function getRevenueAnalytics($today, $thisWeek, $thisMonth, $lastMonth)
    {
        // Today's revenue
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // This week revenue
        $weekRevenue = Order::whereDate('created_at', '>=', $thisWeek)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // This month revenue
        $monthRevenue = Order::whereDate('created_at', '>=', $thisMonth)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Last month revenue
        $lastMonthRevenue = Order::whereBetween('created_at', [$lastMonth, $lastMonth->copy()->endOfMonth()])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // Revenue growth
        $revenueGrowth = $lastMonthRevenue > 0
            ? (($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        // Average order value
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $averageOrderValue = $totalOrders > 0 ? $monthRevenue / $totalOrders : 0;

        return [
            'today' => $todayRevenue,
            'week' => $weekRevenue,
            'month' => $monthRevenue,
            'last_month' => $lastMonthRevenue,
            'growth' => round($revenueGrowth, 2),
            'average_order_value' => round($averageOrderValue, 2),
        ];
    }

    /**
     * Get order analytics.
     */
    private function getOrderAnalytics($today, $thisWeek, $thisMonth)
    {
        // Today's orders
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayCompleted = Order::whereDate('created_at', $today)
            ->where('status', 'delivered')
            ->count();

        // This week orders
        $weekOrders = Order::whereDate('created_at', '>=', $thisWeek)->count();

        // This month orders
        $monthOrders = Order::whereDate('created_at', '>=', $thisMonth)->count();

        // Order status breakdown
        $orderStatus = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'closed' => Order::where('status', 'closed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Delivery success rate
        $totalNonCancelled = Order::where('status', '!=', 'cancelled')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $deliverySuccessRate = $totalNonCancelled > 0
            ? ($deliveredOrders / $totalNonCancelled) * 100
            : 0;

        return [
            'today' => $todayOrders,
            'today_completed' => $todayCompleted,
            'week' => $weekOrders,
            'month' => $monthOrders,
            'status_breakdown' => $orderStatus,
            'delivery_success_rate' => round($deliverySuccessRate, 2),
        ];
    }

    /**
     * Get popular menu items.
     */
    private function getPopularItems()
    {
        $popularItems = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select(
                'menus.id',
                'menus.name',
                'menus.price',
                'menus.category',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.price', 'menus.category')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        return $popularItems;
    }

    /**
     * Get customer analytics.
     */
    private function getCustomerAnalytics($today, $thisWeek, $thisMonth)
    {
        // Total customers
        $totalCustomers = User::where('role', 'client')->count();

        // New customers this month
        $newCustomersThisMonth = User::where('role', 'client')
            ->whereDate('created_at', '>=', $thisMonth)
            ->count();

        // Active customers (placed order in last 30 days)
        $activeCustomers = User::where('role', 'client')
            ->whereHas('orders', function ($query) {
                $query->whereDate('created_at', '>=', Carbon::now()->subDays(30));
            })
            ->count();

        // Repeat customer rate
        $customersWithOrders = User::where('role', 'client')
            ->whereHas('orders')
            ->get();

        $repeatCustomers = $customersWithOrders->filter(function ($customer) {
            return $customer->orders->count() > 1;
        })->count();

        $repeatRate = $customersWithOrders->count() > 0
            ? ($repeatCustomers / $customersWithOrders->count()) * 100
            : 0;

        // Top customers by spending
        $topCustomers = User::where('role', 'client')
            ->withSum('orders', 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->limit(5)
            ->get();

        return [
            'total' => $totalCustomers,
            'new_this_month' => $newCustomersThisMonth,
            'active' => $activeCustomers,
            'repeat_rate' => round($repeatRate, 2),
            'top_customers' => $topCustomers,
        ];
    }

    /**
     * Get daily sales for chart.
     */
    private function getDailySales()
    {
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days->push($date);
        }

        $dailySales = [];
        foreach ($last7Days as $date) {
            $dailySales[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'orders' => Order::whereDate('created_at', $date)->count(),
                'revenue' => Order::whereDate('created_at', $date)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount'),
            ];
        }

        return $dailySales;
    }

    /**
     * Get category performance.
     */
    private function getCategoryPerformance()
    {
        $categoryPerformance = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select(
                'menus.category',
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_items_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('menus.category')
            ->get();

        return $categoryPerformance;
    }

    /**
     * Get peak ordering hours.
     */
    private function getPeakHours()
    {
        $peakHours = DB::table('orders')
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get();

        return $peakHours;
    }

    /**
     * Export analytics report.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'sales');
        $format = $request->get('format', 'csv');

        // Generate report based on type
        // This can be extended to export PDF, Excel, etc.

        return response()->json(['message' => 'Export functionality coming soon']);
    }
}
