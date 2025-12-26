<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\Address;
use App\Models\Payment;

class OrderController extends Controller
{
    public function store()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->where('status', 1)
            ->firstOrFail();

        $subtotal = $cart->items->sum(fn ($i) =>
            $i->quantity * $i->product->price
        );

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'cart_id' => $cart->id,
            'subtotal' => $subtotal,
            'tax' => 0,
            'discount' => 0,
            'total' => $subtotal,
            'status' => 0
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name_snapshot' => $item->product->name,
                'price_snapshot' => $item->product->price,
                'quantity' => $item->quantity,
                'total' => $item->quantity * $item->product->price
            ]);
        }

        // mark cart as converted
        $cart->update(['status' => 0]);

        return response()->json([
            'success' => true,
            'order_id' => $order->id
        ]);
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items');

        return view('orders.show', compact('order'));
    }


    public function addressIndex(Request $request)
    {
        return $request->user()->addresses;
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
        ]);

        $data['user_id'] = $request->user()->id;

        return Address::create($data);
    }

    public function placeOrder(Request $request, Order $order)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id'
        ]);

        $order->update([
            'address_id' => $request->address_id,
            'status' => 'confirmed'
        ]);

        
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'cod',
            'amount' => $order->total,
            'status' => 0
        ]);

        return response()->json([
            'message' => 'Order placed successfully (Cash on Delivery)'
        ]);
    }
}
