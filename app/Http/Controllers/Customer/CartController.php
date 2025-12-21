<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product.images')->get();

        // Check stock availability for all cart items
        $stockWarnings = [];
        $outOfStockItems = [];

        foreach ($cartItems as $item) {
            if ($item->product->isOutOfStock()) {
                $outOfStockItems[] = $item->product->name;
            } elseif ($item->quantity > $item->product->stock) {
                $stockWarnings[] = "{$item->product->name} only has {$item->product->stock} units available (you have {$item->quantity} in cart)";
            } elseif ($item->product->isLowStock()) {
                $stockWarnings[] = "{$item->product->name} is low in stock ({$item->product->stock} left)";
            }
        }

        // Flash messages for stock issues
        if (!empty($outOfStockItems)) {
            session()->flash('error', 'Some items are out of stock: ' . implode(', ', $outOfStockItems));
        }

        if (!empty($stockWarnings)) {
            session()->flash('low_stock_warning', implode('. ', $stockWarnings));
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request, Product $product)
    {
        // Check if product is active and approved
        if (!$product->is_active || !$product->is_approved) {
            return redirect()->back()->with('error', 'This product is not available.');
        }

        // Check if product is out of stock
        if ($product->isOutOfStock()) {
            return redirect()->back()->with('error', 'This product is currently out of stock.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ], [
            'quantity.max' => "Only {$product->stock} units available for this product."
        ]);

        $quantity = $request->quantity;

        // Check if item already exists in cart
        $existingCartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            // Calculate new total quantity
            $newQuantity = $existingCartItem->quantity + $quantity;

            // Check if new quantity exceeds stock
            if ($newQuantity > $product->stock) {
                $remaining = $product->stock - $existingCartItem->quantity;

                if ($remaining <= 0) {
                    return redirect()->back()->with('error',
                        "You already have the maximum available quantity ({$existingCartItem->quantity}) in your cart.");
                }

                return redirect()->back()->with('error',
                    "Cannot add {$quantity} more. Only {$remaining} more units available (you have {$existingCartItem->quantity} in cart).");
            }

            $existingCartItem->update(['quantity' => $newQuantity]);
            $message = 'Cart updated! Quantity increased to ' . $newQuantity;
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
            $message = 'Product added to cart!';
        }

        // Add low stock warning if applicable
        if ($product->isLowStock()) {
            session()->flash('low_stock_warning',
                "Hurry! Only {$product->stock} units left of {$product->name}!");
        }

        return redirect()->route('customer.cart.index')->with('success', $message);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if product is still available
        if ($cartItem->product->isOutOfStock()) {
            return $this->respondWithError(
                $request,
                'This product is now out of stock.',
                $cartItem
            );
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ], [
            'quantity.max' => "Only {$cartItem->product->stock} units available."
        ]);

        $quantity = $request->quantity;

        // Check stock availability
        if (!$cartItem->product->hasStock($quantity)) {
            return $this->respondWithError(
                $request,
                "Only {$cartItem->product->stock} units available.",
                $cartItem
            );
        }

        $cartItem->update(['quantity' => $quantity]);

        // Prepare response data
        $responseData = [
            'success' => true,
            'message' => 'Cart updated',
            'item' => $cartItem->load('product'),
            'stock_status' => $cartItem->product->getStockStatus(),
            'stock_message' => $cartItem->product->getStockMessage()
        ];

        // Add low stock warning if applicable
        if ($cartItem->product->isLowStock()) {
            $responseData['warning'] = "Only {$cartItem->product->stock} units left!";
            session()->flash('low_stock_warning', $responseData['warning']);
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json($responseData);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Cart updated');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $productName = $cartItem->product->name;
        $cartItem->delete();

        return redirect()->route('customer.cart.index')
            ->with('success', "{$productName} removed from cart");
    }

    /**
     * Helper method to respond with error for both JSON and regular requests
     */
    private function respondWithError(Request $request, string $message, CartItem $cartItem)
    {
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'item' => $cartItem->load('product'),
                'stock' => $cartItem->product->stock,
                'stock_status' => $cartItem->product->getStockStatus()
            ], 422);
        }

        return redirect()->route('customer.cart.index')->with('error', $message);
    }
}
