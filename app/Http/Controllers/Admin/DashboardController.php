<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Seluruh metrik diambil riil dari database sesuai Craftsmanship Standard C-5 dan DESIGN.md.
        $stats = [
            'total_products'   => Product::count(),
            'total_categories' => Category::count(),
            'total_customers'  => User::where('role', User::ROLE_CUSTOMER)->count(),
            'total_orders'     => Order::count(),
            'orders_pending'   => Order::where('payment_status', Order::PAYMENT_PENDING)->count(),
            'orders_paid'      => Order::where('payment_status', Order::PAYMENT_PAID)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
