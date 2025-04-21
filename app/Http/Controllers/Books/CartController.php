<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['indexAPI', 'addAPI', 'removeAPI']);
    }

    public function indexAPI(Request $request)
    {
        $cartItems = Carts::where('user_id', $request->user()->id)->with('book')->get();
        return response()->json($cartItems);
    }

    public function addAPI(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Carts::updateOrCreate(
            ['user_id' => $request->user()->id, 'book_id' => $request->book_id],
            ['quantity' => DB::raw("quantity + {$request->quantity}")]
        );

        return response()->json(['message' => 'Buku ditambahkan ke keranjang!'], 201);
    }

    public function removeAPI(Request $request, $id)
    {
        $cartItem = Carts::where('id', $id)->where('user_id', $request->user()->id)->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Item dihapus dari keranjang.'], 200);
        }

        return response()->json(['message' => 'Item tidak ditemukan.'], 404);
    }

    public function index(Request $request)
    {
        $cartItems = $request->user()->cart()->with('book')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        return view('layouts.app', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'cartCount' => $cartItems->count()
        ]);
    }

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

        return redirect()->back()->with('message', 'Buku berhasil ditambahkan ke keranjang!');
    }

    public function remove(Request $request, $id)
    {
        $cartItem = Carts::where('id', $id)->where('user_id', $request->user()->id)->first();

        if ($cartItem) {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $newQuantity = $cartItem->quantity - $request->quantity;

            if ($newQuantity > 0) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $cartItem->delete();
            }

            return redirect()->back()->with('message', 'Item berhasil diupdate!');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan!');
    }
}