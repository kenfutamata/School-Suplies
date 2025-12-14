<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('seller');
    }

    public function index()
    {
        $seller = auth()->user()->seller;

        $totalProducts = $seller->products()->count();
        
        // Get orders that belong to this seller (using seller_id)
        $pendingOrders = Order::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->count();

        $totalRevenue = Order::where('seller_id', $seller->id)
            ->where('status', 'delivered')
            ->sum('total_amount');

        $recentOrders = Order::where('seller_id', $seller->id)
            ->with(['items.product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'seller',
            'totalProducts',
            'pendingOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
