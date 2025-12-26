<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Product;
class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)
        ->with(['stock'])
        ->get()
        ->map(function ($product) {
            $product->stock_quantity = $product->stock->quantity_available ?? 0;
            return $product;
        });

        $cart = auth()->check() ? auth()->user()->cart()->with('items.product')->first() : ['items' => []];
        return view('home', compact('products', 'cart'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with(['orderItems', 'payment', 'address'])
            ->latest()
            ->get();

        $addresses = $user->addresses()->get();

        return view('dashboard', [
            'orders' => $orders,
            'addresses' => $addresses
        ]);
    }
}