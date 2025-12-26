<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('status', 1)
            ->with('items.product')
            ->first();

        return view('cart.index', compact('cart'));
    }

    public function add(Product $product)
    {
        $cart = Cart::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'status' => 1
            ],
            [
                'added_by' => auth()->id()
            ]
        );

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'price_snapshot' => $product->price,
                'quantity' => 1,
                'added_by' => auth()->id()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart'
        ]);
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed'
        ]);
    }
}
