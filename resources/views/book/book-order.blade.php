@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Riwayat Pesanan</h2>

    @if($orders->isEmpty())
        <p class="text-muted">Kamu belum memiliki pesanan.</p>
    @else
        @foreach ($orders as $order)
            <div class="card mb-4 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    Order #{{ $loop->iteration }} &nbsp; | &nbsp; Tanggal: {{ $order->created_at->format('d M Y') }}
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        @foreach ($order->order_items as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $item->book->title }} x{{ $item->quantity }}</span>
                                <span>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <h5 class="text-end">Total: Rp{{ number_format($order->total_price, 0, ',', '.') }}</h5>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
