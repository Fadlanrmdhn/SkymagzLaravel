@extends('templateUser.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center align-items-start">
            <!-- Gambar Cover -->
            <div class="col-md-4 text-center">
                @if ($magazine->cover)
                    <img src="{{ asset('storage/' . $magazine->cover) }}"
                         alt="{{ $magazine->title }}"
                         class="img-fluid rounded-4 shadow-sm mb-3"
                         style="max-height: 420px; object-fit: cover;">
                @else
                    <img src="{{ asset('images/default-magazine.jpg') }}"
                         alt="Default Cover"
                         class="img-fluid rounded-4 shadow-sm mb-3"
                         style="max-height: 420px; object-fit: cover;">
                @endif
            </div>

            <!-- Detail Buku & Pembelian -->
            <div class="col-md-7">
                <h2 class="fw-bold">{{ $magazine->title }}</h2>
                <p class="text-muted mb-2">Penulis: <strong>{{ $magazine->author }}</strong></p>
                <p class="text-muted mb-2">Penerbit: <strong>{{ $magazine->publisher }}</strong></p>
                <p class="text-muted mb-3">
                    Tanggal Rilis:
                    <strong>{{ \Carbon\Carbon::parse($magazine->release_date)->translatedFormat('d F Y') }}</strong>
                </p>

                <div class="mb-3">
                    <h5 class="fw-semibold mb-2">Kategori</h5>
                    @if ($magazine->categories && $magazine->categories->count())
                        @foreach ($magazine->categories as $category)
                            <span class="badge bg-primary me-1">{{ $category->name }}</span>
                        @endforeach
                    @else
                        <span class="text-muted">Tidak ada kategori</span>
                    @endif
                </div>

                <hr>

                <h5 class="fw-semibold mb-2">Deskripsi</h5>
                <p class="text-secondary" style="text-align: justify;">
                    {{ $magazine->description }}
                </p>

                <hr>

                <!-- Informasi Harga & Form Pembelian -->
                <div class="mt-4">

                    <!-- Harga + Kembali -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="fw-bold fs-5 text-success mb-0">
                            Rp <span id="unitPrice">{{ number_format($magazine->price, 0, ',', '.') }}</span>
                        </p>

                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-3">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <!-- Pesan sukses / error -->
                    @if (session('success'))
                        <div class="alert alert-success py-2">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger py-2">{{ session('error') }}</div>
                    @endif

                    <!-- Form Pembelian -->
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="magazine_id" value="{{ $magazine->id }}">

                        <div class="row g-2 align-items-center mb-3">
                            <input type="hidden" name="quantity" id="quantity" value="1">

                            <div class="col-auto">
                                <label class="form-label">Total</label>
                                <div class="fs-5 fw-semibold">
                                    Rp <span id="totalPrice">{{ number_format($magazine->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <!-- Add to Cart -->
                            <button type="submit" name="action" value="cart" class="btn btn-primary rounded-3">
                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                            </button>

                            <!-- Buy Now -->
                            <button type="submit" name="action" value="buy" class="btn btn-success rounded-3">
                                <i class="bi bi-bag-check"></i> Beli Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Script Rumus Total -->
        @push('scripts')
            <script>
                (function() {
                    const unitPrice = Number({{ $magazine->price }});
                    const qtyInput = document.getElementById('quantity');
                    const totalEl = document.getElementById('totalPrice');

                    function formatRupiah(number) {
                        return new Intl.NumberFormat('id-ID').format(number);
                    }

                    function updateTotal() {
                        const qty = qtyInput ? Number(qtyInput.value) : 1;
                        const total = unitPrice * (qty > 0 ? qty : 1);
                        totalEl.textContent = formatRupiah(total);
                    }

                    updateTotal();
                })();
            </script>
        @endpush
    </div>
@endsection
