<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('service')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->service->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, Service $service)
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('service_id', $service->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'service_id' => $service->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Услуга добавлена в корзину');
    }

    public function update(Request $request, CartItem $cartItem)
    {

        if ($request->action === 'increase') {
            $cartItem->increment('quantity');
        } elseif ($request->action === 'decrease') {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
        }

        return redirect()->route('cart');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();

        return redirect()->route('cart')->with('success', 'Услуга удалена из корзины');
    }

    public function checkout()
    {
        $cartItems = Auth::user()->cartItems()->with('service')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Ваша корзина пуста');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->service->price * $item->quantity;
        });

        $order = Auth::user()->orders()->create([
            'status' => \App\Enums\OrderStatus::NEW->value,
            'total_price' => $total,
        ]);

        foreach ($cartItems as $cartItem) {
            $order->items()->create([
                'service_id' => $cartItem->service_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->service->price,
            ]);
        }

        Auth::user()->cartItems()->delete();

        return redirect()->route('profile')->with('success', 'Заказ успешно оформлен');
    }
}
