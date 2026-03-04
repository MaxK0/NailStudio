<?php

namespace App\Http\Controllers;

use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $orders = auth()
            ->user()
            ->orders()
            ->with('items.service')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('profile', compact('orders'));
    }
}
