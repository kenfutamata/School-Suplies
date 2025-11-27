<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()->with(['items.product'])->latest()->take(5)->get();
        $cartCount = $user->cartItems()->count();
        $wishlistCount = $user->wishlists()->count();
        $orderCount = $user->orders()->count();

        return view('customer.dashboard', compact('recentOrders', 'cartCount', 'wishlistCount', 'orderCount'));
    }
}
