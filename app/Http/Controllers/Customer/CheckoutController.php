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
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('customer.checkout.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_latitude' => 'nullable|string',
            'shipping_longitude' => 'nullable|string',
            'payment_method' => 'required|in:gcash,card',
        ]);

        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $user = auth()->user();
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        try {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'shipping_latitude' => $request->shipping_latitude,
                'shipping_longitude' => $request->shipping_longitude,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                ]);

                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $request->payment_method,
                'status' => 'pending',
                'amount' => $total,
            ]);
            $orderNumber = $order->order_number;
            $mailData = [
                'order' => $order,
                'name'=> $user->name,
                'items'=> $cartItems,
                'order_number'=> $orderNumber,
                'total'=> $total,
            ];
            Mail::to($user->email)->send(new sendOrderDetailsMail($mailData));
            auth()->user()->cartItems()->delete();
            return redirect()->route('customer.orders.index')->with('success', 'Order created successfully');
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
