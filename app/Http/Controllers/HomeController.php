<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->where('is_approved', true)
            ->with(['images', 'seller'])
            ->latest()
            ->take(12)
            ->get();

        return view('home', compact('products'));
    }
}
