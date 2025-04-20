<?php

namespace App\Http\Controllers\Books;

use App\Models\Books;
use App\Models\Carts;
use App\Models\Orders;
use App\Models\Order_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Melakukan checkout
    public function checkoutAPI(Request $request)
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
    public function successAPI(Request $request)
    {
        return response()->json(['message' => 'Order berhasil!'], 200);
    }
    
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $bookId = $request->get('book_id');
        $quantity = $request->get('quantity', 1);
        
        // Check if it's a direct purchase (not from cart)
        if ($bookId) {
            $book = Books::find($bookId);
            if (!$book) {
                return redirect()->back()->with('error', 'Buku tidak ditemukan.');
            }
            
            $total = $book->price * $quantity;
            
            DB::beginTransaction();
            try {
                $order = Orders::create([
                    'user_id' => $user->id,
                    'total_price' => $total,
                ]);

                Order_items::create([
                    'order_id' => $order->id,
                    'book_id' => $bookId,
                    'quantity' => $quantity,
                    'price' => $book->price,
                ]);

                DB::commit();

                return view('book.book-checkout', [
                    'cartItems' => [],
                    'total' => 0,
                    'showSuccessPopup' => true,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage());
            }
        }
        
        // Regular cart checkout flow
        $cartItems = Carts::with('book')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kamu kosong.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->book->price * $item->quantity;
        });

        DB::beginTransaction();
        try {
            $order = Orders::create([
                'user_id' => $user->id,
                'total_price' => $total,
            ]);

            foreach ($cartItems as $item) {
                Order_items::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->book->price,
                ]);
            }

            Carts::where('user_id', $user->id)->delete();

            DB::commit();

            return view('book.book-checkout', [
                'cartItems' => [],
                'total' => 0,
                'showSuccessPopup' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    public function showCheckoutPage(Request $request)
    {
        $user = Auth::user();
        $bookId = $request->get('book_id');
        $quantity = (int)$request->get('quantity', 1);
        
        // If bookId is provided, it's a direct purchase
        if ($bookId) {
            $book = Books::find($bookId);
            if ($book) {
                // Create a collection similar to cart items format
                $cartItems = collect([
                    (object)[
                        'book' => $book,
                        'quantity' => $quantity,
                    ]
                ]);
                
                $total = $book->price * $quantity;
                
                return view('book.book-checkout', compact('cartItems', 'total'));
            }
        }
        
        // Default behavior - get items from cart
        $cartItems = Carts::with('book')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->book->price * $item->quantity;
        });

        return view('book.book-checkout', compact('cartItems', 'total'));
    }

    public function success()
    {
        return redirect()->route('books.view')->with('success_popup', true);
    }
    
    public function index()
    {
        $user = Auth::user();

        $orders = Orders::with(['order_items.book'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('book.book-order', compact('orders'));
    }
    
    public function indexAPI(Request $request)
    {
        $user = $request->user(); // Mengambil user dari request yang sudah terautentikasi

        // Mengambil pesanan dengan relasi order_items dan book
        $orders = Orders::with(['order_items.book'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengembalikan response JSON
        return response()->json([
            'orders' => $orders
        ], 200);
    }
}