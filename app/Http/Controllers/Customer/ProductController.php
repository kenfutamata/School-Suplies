<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('is_approved', true)
            ->with(['images', 'seller']);

        $search = $request->input('search') ?? $request->input('query') ?? null;
        if (!empty($search) && trim($search) !== '') {
            $search = trim($search);

            $query->where(function ($q) use ($search) {
                $searchLower = mb_strtolower($search, 'UTF-8');
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchLower . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $searchLower . '%']);
            });
        }

        // Category filter: use case-insensitive LIKE match to handle partial matches and avoid mismatches
        $category = $request->input('category');
        if (!empty($category) && trim($category) !== '') {
            $category = trim($category);
            // Use case-insensitive LIKE for partial matching to handle variations
            $categoryLower = mb_strtolower($category, 'UTF-8');
            $query->whereRaw('LOWER(category) LIKE ?', ['%' . $categoryLower . '%']);
        }

        $products = $query->latest()->paginate(12);

        $wishlistProductIds = [];
        if (auth()->check() && auth()->user()->isCustomer()) {
            $wishlistProductIds = auth()->user()->wishlists()->pluck('product_id')->toArray();
        }

        return view('customer.products.index', [
            'products' => $products,
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['images', 'seller']);

        $isWishlisted = false;
        if (auth()->check() && auth()->user()->isCustomer()) {
            $isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
        }

        return view('customer.products.show', compact('product', 'isWishlisted'));
    }
}
