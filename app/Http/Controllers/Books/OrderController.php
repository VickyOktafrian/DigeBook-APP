<?php

namespace App\Http\Controllers\Books;

use App\Models\Carts;
use App\Models\Orders;
use App\Models\Order_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;  // Pastikan menggunakan Controller umum

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Melakukan checkout
    public function checkout(Request $request)
    {
        // Proses checkout: bisa melakukan validasi, menghitung total harga, dan lainnya
        $cartItems = Carts::where('user_id', $request->user()->id)->get();
        
        // Contoh logika checkout (misalnya membuat order, mengurangi stock, dll)
        $order = Orders::create([
            'user_id' => $request->user()->id,
            'total_price' => $cartItems->sum('price'), // misalnya total harga
        ]);

        // Hapus semua item keranjang setelah checkout
        Carts::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'message' => 'Checkout berhasil!',
            'order' => $order
        ], 201);
    }

    // Status sukses checkout
    public function success(Request $request)
    {
        return response()->json(['message' => 'Order berhasil!'], 200);
    }
}

