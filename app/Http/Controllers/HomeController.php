<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Product;
class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)->get();
        return view('home', compact('products'));
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