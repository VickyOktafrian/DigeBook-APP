@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Konfirmasi Checkout</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif (empty($cartItems) || count($cartItems) == 0)
        <div class="alert alert-warning">Keranjang kamu kosong.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td>{{ $item->book->title }}</td>
                        <td>Rp {{ number_format($item->book->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->book->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Total: Rp {{ number_format($total, 0, ',', '.') }}</h4>

        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            
            {{-- Pass direct purchase parameters if present --}}
            @if(request()->has('book_id'))
                <input type="hidden" name="book_id" value="{{ request()->get('book_id') }}">
                <input type="hidden" name="quantity" value="{{ request()->get('quantity', 1) }}">
            @endif
            
            <button type="submit" class="btn btn-primary">Konfirmasi & Bayar</button>
        </form>
    @endif
</div>

{{-- Modal Sukses --}}
@if (session('showSuccessPopup') || (isset($showSuccessPopup) && $showSuccessPopup))
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-lg text-center max-w-md w-full animate-fade-in">
            <h2 class="text-2xl font-bold text-green-600 mb-4">✅ Checkout Berhasil!</h2>
            <p class="text-gray-700 mb-6">Terima kasih! Pesanan kamu sedang kami proses.</p>
            <button onclick="redirectToBooks()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Tutup & Kembali ke Buku
            </button>
        </div>
    </div>

    <script>
        function redirectToBooks() {
            window.location.href = "{{ route('books.view') }}";
        }
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
@endif
@endsection