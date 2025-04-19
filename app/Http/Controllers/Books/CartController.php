<?php

namespace App\Http\Controllers\Books;

use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;  // Menggunakan Controller Laravel

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Menampilkan daftar item keranjang
    public function index(Request $request)
    {
        $cartItems = Carts::where('user_id', $request->user()->id)->with('book')->get();
        return response()->json($cartItems); // Menampilkan item keranjang dalam format JSON
    }

    // Menambahkan buku ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Carts::updateOrCreate(
            ['user_id' => $request->user()->id, 'book_id' => $request->book_id],
            ['quantity' => DB::raw("quantity + {$request->quantity}")]
        );

        return response()->json(['message' => 'Buku ditambahkan ke keranjang!'], 201); // Response sukses dalam JSON
    }

    // Menghapus item dari keranjang
    public function remove(Request $request, $id)
    {
        $cartItem = Carts::where('id', $id)->where('user_id', $request->user()->id)->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Item dihapus dari keranjang.'], 200);
        }

        return response()->json(['message' => 'Item tidak ditemukan.'], 404);
    }
}

