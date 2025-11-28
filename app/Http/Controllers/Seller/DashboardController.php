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
        $pendingOrders = Order::whereHas('items.product', function ($query) use ($seller) {
            $query->where('seller_id', $seller->id);
        })->where('status', 'pending')->count();

        $totalRevenue = Order::whereHas('items.product', function ($query) use ($seller) {
            $query->where('seller_id', $seller->id);
        })->where('status', 'Delivered')->sum('total_amount');

        $recentOrders = Order::whereHas('items.product', function ($query) use ($seller) {
            $query->where('seller_id', $seller->id);
        })->with(['items.product', 'user'])->latest()->take(5)->get();

        return view('seller.dashboard', compact(
            'seller',
            'totalProducts',
            'pendingOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
