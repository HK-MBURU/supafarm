<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Get real statistics
        $stats = [
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'totalProducts' => Product::where('is_active', true)->count(),
            'totalCategories' => Category::where('is_active', true)->count(),
            'totalCustomers' => Customer::count(),
            'totalContacts' => Contact::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'todayOrders' => Order::whereDate('created_at', Carbon::today())->count(),
            'thisWeekRevenue' => Order::where('status', 'delivered')
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->sum('total_amount'),
        ];

        // Calculate success rate (delivered orders vs total orders)
        $totalDelivered = Order::where('status', 'delivered')->count();
        $stats['successRate'] = $stats['totalOrders'] > 0
            ? round(($totalDelivered / $stats['totalOrders']) * 100)
            : 0;

        // Get recent orders
        $recentOrders = Order::with(['customer'])
            ->latest()
            ->take(8)
            ->get();

        // Get low stock products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('is_active', true)
            ->orderBy('stock')
            ->take(5)
            ->get();

        // Get recent contacts
        $recentContacts = Contact::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts', 'recentContacts'));
    }
}
