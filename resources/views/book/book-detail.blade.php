@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Gambar Buku -->
        <div class="col-md-5">
            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="img-fluid rounded shadow-sm">
        </div>

        <!-- Info Buku -->
        <div class="col-md-7">
            <h2>{{ $book->title }}</h2>
            <p class="text-muted">Penulis: <strong>{{ $book->author }}</strong></p>
            <h4 class="text-danger mb-3">Rp{{ number_format($book->price) }}</h4>

            <p>{{ $book->description }}</p>

            <form action="{{ route('cart.add') }}" method="POST" class="mt-4 d-flex flex-column gap-3">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">

                <div class="form-group">
                    <label for="quantity">Jumlah:</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" class="form-control w-25">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                    </button>

                    <!-- Tombol Beli Sekarang sebagai Link -->
                    <a href="{{ route('checkout.view', ['book_id' => $book->id, 'quantity' => request()->get('quantity', 1)]) }}" class="btn btn-success">
                        <i class="bi bi-bag-check"></i> Beli Sekarang
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
