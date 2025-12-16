@extends('templateUser.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <!-- Left: Order Summary -->
        <div class="col-md-6">
            <h2 class="mb-4">Ringkasan Pesanan</h2>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $magazine_id => $item)
                                    @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                    <tr>
                                        <td>{{ Str::limit($item['title'], 30) }}</td>
                                        <td class="text-end">{{ $item['quantity'] }}</td>
                                        <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Total:</td>
                                    <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
        </div>

        <!-- Right: Checkout Form -->
        <div class="col-md-6">
            <h2 class="mb-4">Data Pengiriman & Pembayaran</h2>

            <div class="card shadow-sm">
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <!-- User Info (Read-only) -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                        </div>

                        <!-- Promo Selection -->
                        <div class="mb-4">
                            <label for="promo_id" class="form-label">Pilih Promo (Opsional)</label>
                            <div class="d-flex gap-2">
                                <select class="form-select" name="promo_id" id="promo_id" onchange="applyPromo()">
                                    <option value="">-- Tidak ada promo --</option>
                                    @foreach($promos as $promo)
                                        <option value="{{ $promo->id }}" {{ $selectedPromoId == $promo->id ? 'selected' : '' }}>
                                            {{ $promo->promo_code }}
                                            @if(str_contains(strtolower($promo->type), 'perc'))
                                                ({{ $promo->discount }}% Off)
                                            @else
                                                (Rp {{ number_format($promo->discount, 0, ',', '.') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" onclick="applyPromo()">Terapkan</button>
                            </div>
                        </div>
                        <input type="hidden" name="promo_discount" id="promo_discount" value="{{ $initialPromoDiscount }}">
                        <input type="hidden" name="promo_id_hidden" id="promo_id_hidden" value="{{ $selectedPromoId }}">

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="transfer" required>
                                        <label class="form-check-label" for="transfer">
                                            <i class="bi bi-bank"></i> Transfer Bank
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="card" value="card" required>
                                        <label class="form-check-label" for="card">
                                            <i class="bi bi-credit-card"></i> Kartu Kredit
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="gopay" value="gopay" required>
                                        <label class="form-check-label" for="gopay">
                                            <i class="bi bi-wallet"></i> GoPay
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="ovo" value="ovo" required>
                                        <label class="form-check-label" for="ovo">
                                            <i class="bi bi-wallet"></i> OVO
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Order Summary -->
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between" id="discount_row" style="display: {{ $initialPromoDiscount > 0 ? 'flex' : 'none' }};">
                                <span id="promo_code_display">Diskon (@if($selectedPromoId) {{ $promos->find($selectedPromoId)->promo_code ?? '' }} @endif):</span>
                                <span class="text-danger" id="discount_amount">-Rp {{ number_format($initialPromoDiscount, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-2" id="discount_hr" style="display: {{ $initialPromoDiscount > 0 ? 'block' : 'none' }};">
                            <div class="d-flex justify-content-between">
                                <strong>Total Pembayaran:</strong>
                                <strong id="total_payment">Rp {{ number_format($total - $initialPromoDiscount, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('cart') }}" class="btn btn-secondary flex-grow-1">
                                <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
                            </a>
                            <button type="submit" class="btn btn-success flex-grow-1">
                                <i class="bi bi-check-circle"></i> Lanjutkan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

<script>
    const promos = @json($promos);
    const total = {{ $total }};

    function applyPromo() {
        const promoId = document.getElementById('promo_id').value;
        const promoDiscountInput = document.getElementById('promo_discount');
        const promoIdHidden = document.getElementById('promo_id_hidden');
        const discountRow = document.getElementById('discount_row');
        const discountHr = document.getElementById('discount_hr');
        const promoCodeDisplay = document.getElementById('promo_code_display');
        const discountAmount = document.getElementById('discount_amount');
        const totalPayment = document.getElementById('total_payment');

        let discount = 0;
        let promoCode = '';

        if (promoId) {
            const promo = promos.find(p => p.id == promoId);
            if (promo) {
                promoCode = promo.promo_code;
                if (promo.type.toLowerCase().includes('perc')) {
                    discount = Math.floor((total * promo.discount) / 100);
                } else {
                    discount = Math.min(promo.discount, total);
                }
            }
        }

        // Update hidden fields
        promoDiscountInput.value = discount;
        promoIdHidden.value = promoId;

        // Update display
        if (discount > 0) {
            promoCodeDisplay.textContent = `Diskon (${promoCode}):`;
            discountAmount.textContent = `-Rp ${discount.toLocaleString('id-ID')}`;
            discountRow.style.display = 'flex';
            discountHr.style.display = 'block';
        } else {
            discountRow.style.display = 'none';
            discountHr.style.display = 'none';
        }

        const finalTotal = total - discount;
        totalPayment.textContent = `Rp ${finalTotal.toLocaleString('id-ID')}`;
    }

    // Apply promo on page load if selected
    document.addEventListener('DOMContentLoaded', function() {
        applyPromo();
    });
</script>
