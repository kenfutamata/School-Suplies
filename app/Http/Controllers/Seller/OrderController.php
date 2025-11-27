<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('seller');
    }

    public function index()
    {
        $seller = auth()->user()->seller;
        
        $orders = Order::whereHas('items.product', function ($query) use ($seller) {
            $query->where('seller_id', $seller->id);
        })
        ->with(['items.product', 'user'])
        ->latest()
        ->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $seller = auth()->user()->seller;
        
        $hasSellerProducts = $order->items->contains(function ($item) use ($seller) {
            return $item->product->seller_id === $seller->id;
        });

        if (!$hasSellerProducts) {
            abort(403);
        }

        $order->load(['items.product.images', 'user', 'payment']);
        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $seller = auth()->user()->seller;
        
        $hasSellerProducts = $order->items->contains(function ($item) use ($seller) {
            return $item->product->seller_id === $seller->id;
        });

        if (!$hasSellerProducts) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        // Create notification for customer
        $order->user->notifications()->create([
            'type' => 'order_update',
            'title' => 'Order Status Updated',
            'message' => "Your order #{$order->order_number} has been updated to {$request->status}",
            'related_id' => $order->id,
            'related_type' => Order::class,
        ]);

        return back()->with('success', 'Order status updated successfully');
    }
}
