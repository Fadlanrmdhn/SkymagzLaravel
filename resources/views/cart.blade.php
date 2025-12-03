@extends('templateUser.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Keranjang Belanja</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(!empty($cart) && count($cart) > 0)
        @php $total = 0; @endphp

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Buku</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $magazine_id => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                @if(!empty($item['cover']))
                                    <img src="{{ asset('storage/' . $item['cover']) }}" alt="{{ $item['title'] }}" style="width: 60px; height: auto; border-radius: 4px; object-fit: cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item['title'] }}</strong>
                            </td>
                            <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <form action="{{ route('cart.remove', $magazine_id) }}" method="POST" onsubmit="return confirm('Hapus buku ini dari keranjang?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Promo Selection -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Pilih Promo (Opsional)</h5>
                <form action="{{ route('checkout') }}" method="GET">
                    <div class="mb-3">
                        <select class="form-select" name="promo_id">
                            <option value="">-- Tidak ada promo --</option>
                            @foreach($promos as $promo)
                                <option value="{{ $promo->id }}">
                                    {{ $promo->promo_code }}
                                    @if($promo->type === 'percent')
                                        ({{ $promo->discount }}% Off)
                                    @else
                                        (Rp {{ number_format($promo->discount, 0, ',', '.') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-between gap-2 flex-wrap">
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Lanjutkan Belanja
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Kosongkan Keranjang
                            </button>
                        </form>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-bag-check"></i> Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Hidden form untuk checkout (akan update nanti dengan payment gateway) --}}
        <form id="checkoutForm" action="" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
            <span>Keranjang kosong. <a href="{{ route('home') }}">Kembali ke daftar buku</a></span>
        </div>
    @endif
</div>
@endsection
