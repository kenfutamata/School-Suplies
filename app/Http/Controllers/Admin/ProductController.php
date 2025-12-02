<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
{
    $query = Product::with(['seller.user', 'images'])
                    ->where('is_active', true); // only active products

    if ($request->has('status')) {
        if ($request->status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($request->status === 'approved') {
            $query->where('is_approved', true);
        }
    }

    $products = $query->latest()->paginate(15);
    return view('admin.products.index', compact('products'));
}


    public function approve(Product $product)
    {
        $product->update(['is_approved' => true, 'status' => Product::STATUS_APPROVED]);

        // Notify seller
        $product->seller->user->notifications()->create([
            'type' => 'product_approved',
            'title' => 'Product Approved',
            'message' => "Your product '{$product->name}' has been approved",
            'related_id' => $product->id,
            'related_type' => Product::class,
        ]);

        return back()->with('success', 'Product approved successfully');
    }

    public function reject(Product $product)
    {
        $product->update(['is_approved' => false, 'is_active' => false, 'status' => Product::STATUS_REJECTED]);
        return back()->with('success', 'Product rejected');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully');
    }
}
