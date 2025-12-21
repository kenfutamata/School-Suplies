<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch active and approved products with stock information
        $products = Product::where('is_active', true)
            ->where('is_approved', true)
            ->with(['images', 'seller'])
            ->orderByRaw('CASE
                WHEN stock <= 0 THEN 3
                WHEN stock <= 5 THEN 1
                WHEN stock > 5 THEN 2
            END') // Prioritize low stock items, then in-stock, then out-of-stock
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        return view('home', compact('products'));
    }

    /**
     * Get stock status for a product (for AJAX requests)
     */
    public function getStockStatus($productId)
    {
        $product = Product::findOrFail($productId);

        $status = 'in_stock';
        if ($product->stock <= 0) {
            $status = 'out_of_stock';
        } elseif ($product->stock <= 5) {
            $status = 'low_stock';
        }

        return response()->json([
            'stock' => $product->stock,
            'status' => $status,
            'message' => $this->getStockMessage($product->stock)
        ]);
    }

    /**
     * Get appropriate stock message
     */
    private function getStockMessage($stock)
    {
        if ($stock <= 0) {
            return 'Out of stock';
        } elseif ($stock <= 5) {
            return "Only {$stock} left in stock!";
        } elseif ($stock <= 10) {
            return "Low stock - {$stock} available";
        } else {
            return "{$stock} in stock";
        }
    }
}
