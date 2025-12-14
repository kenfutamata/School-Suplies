<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\sendOrderDetailsMail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Get selected cart item IDs from the request
        $selectedItemIds = $request->input('selected_items', []);
        
        if (empty($selectedItemIds)) {
            return redirect()->route('customer.cart.index')->with('error', 'Please select at least one item to checkout');
        }

        // Get only the selected cart items
        $cartItems = auth()->user()->cartItems()
            ->whereIn('id', $selectedItemIds)
            ->with('product.seller')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Selected items not found');
        }

        // Validate ownership
        foreach ($cartItems as $item) {
            if ($item->user_id !== auth()->id()) {
                return redirect()->route('customer.cart.index')->with('error', 'Unauthorized access to cart items');
            }
        }

        // Calculate total
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Group items by seller for display
        $itemsBySeller = $cartItems->groupBy('product.seller_id');

        return view('customer.checkout.index', compact('cartItems', 'total', 'itemsBySeller', 'selectedItemIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:cart_items,id',
            'shipping_address' => 'required|string',
            'shipping_latitude' => 'nullable|string',
            'shipping_longitude' => 'nullable|string',
            'payment_method' => 'required|in:gcash,card',
        ]);

        $selectedItemIds = $request->input('selected_items');
        $user = auth()->user();

        // Get only the selected cart items
        $cartItems = $user->cartItems()
            ->whereIn('id', $selectedItemIds)
            ->with('product.seller')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Selected items not found');
        }

        // Validate ownership and availability
        foreach ($cartItems as $cartItem) {
            if ($cartItem->user_id !== auth()->id()) {
                return redirect()->route('customer.cart.index')->with('error', 'Unauthorized access to cart items');
            }
            if ($cartItem->quantity > $cartItem->product->stock) {
                return redirect()->route('customer.cart.index')->with('error', "Insufficient stock for {$cartItem->product->name}");
            }
        }

        try {
            // Group cart items by seller_id
            $itemsBySeller = $cartItems->groupBy('product.seller_id');
            $createdOrders = collect([]);

            // Create one order per seller
            foreach ($itemsBySeller as $sellerId => $sellerItems) {
                // Calculate total for this seller's items
                $sellerTotal = $sellerItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                // Create order for this seller
                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'user_id' => auth()->id(),
                    'seller_id' => $sellerId,
                    'total_amount' => $sellerTotal,
                    'status' => 'pending',
                    'shipping_address' => $request->shipping_address,
                    'shipping_latitude' => $request->shipping_latitude,
                    'shipping_longitude' => $request->shipping_longitude,
                ]);

                // Create order items for this seller
                foreach ($sellerItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price,
                        'subtotal' => $cartItem->product->price * $cartItem->quantity,
                    ]);

                    // Decrement stock
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }

                // Create payment for this order
                Payment::create([
                    'order_id' => $order->id,
                    'method' => $request->payment_method,
                    'status' => 'pending',
                    'amount' => $sellerTotal,
                ]);

                $createdOrders->push($order);
            }

            // Send email for each order separately
            foreach ($createdOrders as $order) {
                $orderItems = $order->items()->with('product')->get();
                $mailData = [
                    'order' => $order,
                    'order_number' => $order->order_number,
                    'name' => $user->name,
                    'items' => $orderItems,
                    'total' => $order->total_amount,
                ];
                Mail::to($user->email)->send(new sendOrderDetailsMail($mailData));
            }

            // Remove only the selected cart items
            CartItem::whereIn('id', $selectedItemIds)->delete();

            $orderCount = count($createdOrders);
            $message = $orderCount > 1 
                ? "{$orderCount} orders created successfully" 
                : "Order created successfully";

            return redirect()->route('customer.orders.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your order: ' . $e->getMessage());
        }
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.checkout.payment', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $payment = $order->payment;

        if ($payment->method === 'gcash') {
            $request->validate([
                'gcash_number' => 'required|string',
                'gcash_name' => 'required|string',
            ]);

            $payment->update([
                'reference' => 'GCASH-' . strtoupper(Str::random(10)),
                'status' => 'completed',
            ]);
        } else {
            $request->validate([
                'card_number' => 'required|string',
                'card_name' => 'required|string',
                'card_expiry' => 'required|string',
                'card_cvv' => 'required|string',
            ]);

            $payment->update([
                'reference' => 'CARD-' . strtoupper(Str::random(10)),
                'status' => 'completed',
            ]);
        }

        $order->update(['status' => 'processing']);

        return redirect()->route('customer.orders.show', $order)->with('success', 'Payment processed successfully');
    }
}
