<?php
namespace App\Http\Controllers\Books;

use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['indexAPI', 'addAPI', 'removeAPI']); // Keep middleware for API routes only
    }

    // Menampilkan daftar item keranjang (untuk API)
    public function indexAPI(Request $request)
    {
        $cartItems = Carts::where('user_id', $request->user()->id)->with('book')->get();
        return response()->json($cartItems); // Menampilkan item keranjang dalam format JSON
    }

    // Menambahkan buku ke keranjang (untuk API)
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

    // Menghapus item dari keranjang (untuk API)
    public function removeAPI(Request $request, $id)
    {
        $cartItem = Carts::where('id', $id)->where('user_id', $request->user()->id)->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Item dihapus dari keranjang.'], 200);
        }

        return response()->json(['message' => 'Item tidak ditemukan.'], 404);
    }

    // Menampilkan daftar item keranjang (untuk Web)
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


    // Menambahkan buku ke keranjang (untuk Web)
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Add or update the cart item
        $cartItem = Carts::updateOrCreate(
            ['user_id' => $request->user()->id, 'book_id' => $request->book_id],
            ['quantity' => DB::raw("quantity + {$request->quantity}")]
        );

        // Redirect back to the same page (stay on the current page)
        return redirect()->back()->with('message', 'Buku berhasil ditambahkan ke keranjang!');
    }

    // Menghapus item dari keranjang (untuk Web)
    public function remove(Request $request, $id)
{
    // Temukan item cart yang sesuai dengan ID dan user yang sedang login
    $cartItem = Carts::where('id', $id)->where('user_id', $request->user()->id)->first();

    if ($cartItem) {
        // Validasi jika ada quantity yang diberikan untuk dihapus
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Kurangi quantity yang ingin dihapus dari item
        $newQuantity = $cartItem->quantity - $request->quantity;

        if ($newQuantity > 0) {
            // Jika quantity masih lebih dari 0, update quantity-nya
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Jika quantity sudah 0, hapus item dari cart
            $cartItem->delete();
        }

        return redirect()->back()->with('message', 'Item berhasil diupdate!');
    }

    return redirect()->back()->with('error', 'Item tidak ditemukan!');
}

}
