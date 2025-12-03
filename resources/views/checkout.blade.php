@extends('templateUser.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <!-- Left: Order Summary -->
        <div class="col-md-6">
            <h2 class="mb-4">Ringkasan Pesanan</h2>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @if(!empty($cart) && count($cart) > 0)
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
                    @endif
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
                            <label for="promo_select" class="form-label">Pilih Promo (Opsional)</label>
                            <select class="form-select" id="promo_select">
                                <option value="">-- Tidak ada promo --</option>
                                @foreach($promos as $promo)
                                    <option value="{{ $promo->id }}" data-discount="{{ $promo->discount }}" data-type="{{ $promo->type }}">
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
                        <input type="hidden" id="promoDiscount" name="promo_discount" value="0">
                        <input type="hidden" id="promoId" name="promo_id" value="">

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
                                <span id="subtotalDisplay">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between" id="discountRow" style="display: none;">
                                <span>Diskon (<span id="promoCodeDisplay"></span>):</span>
                                <span id="discountDisplay"></span>
                            </div>
                            <hr class="my-2" id="dividerLine">
                            <div class="d-flex justify-content-between">
                                <strong>Total Pembayaran:</strong>
                                <strong id="totalDisplay">Rp {{ number_format($total, 0, ',', '.') }}</strong>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const subtotal = {{ $total }};
    const initialPromoId = '{{ $selectedPromoId ?? '' }}';
    const initialPromoDiscount = {{ $initialPromoDiscount ?? 0 }};
    const promoSelect = document.getElementById('promo_select');
    const promoDiscountField = document.getElementById('promoDiscount');
    const promoIdField = document.getElementById('promoId');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const discountDisplay = document.getElementById('discountDisplay');
    const promoCodeDisplay = document.getElementById('promoCodeDisplay');
    const discountRow = document.getElementById('discountRow');
    const totalDisplay = document.getElementById('totalDisplay');

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function updateTotal(discount = 0) {
        const total = Math.max(subtotal - discount, 0);
        promoDiscountField.value = discount;
        subtotalDisplay.textContent = 'Rp ' + formatRupiah(subtotal);
           if (discount > 0) {
              discountDisplay.innerHTML = '<span class="text-danger">-Rp ' + formatRupiah(discount) + '</span>';
           }
           totalDisplay.textContent = 'Rp ' + formatRupiah(total);

        if (discount > 0) {
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }
    }

    promoSelect.addEventListener('change', function () {
        const selectedOption = promoSelect.options[promoSelect.selectedIndex];

        if (!selectedOption.value) {
            // No promo selected
            promoIdField.value = '';
            promoCodeDisplay.textContent = '';
            updateTotal(0);
            return;
        }

        const discount = parseInt(selectedOption.dataset.discount) || 0;
        const type = selectedOption.dataset.type || '';
        const promoCode = selectedOption.text.split(' ')[0];

        // Calculate discount: support several type names (percent, percentage, fixed, amount)
        let finalDiscount = 0;
        const t = type.toString().toLowerCase();
        if (t.includes('perc')) {
            finalDiscount = Math.floor((subtotal * discount) / 100);
        } else {
            finalDiscount = Math.min(discount, subtotal);
        }

        promoIdField.value = selectedOption.value;
        promoCodeDisplay.textContent = promoCode;
        updateTotal(finalDiscount);
    });

    // If nav from cart included a promo, pre-select it and update totals
    if (initialPromoId) {
        const opt = Array.from(promoSelect.options).find(o => o.value === initialPromoId);
        if (opt) {
            promoSelect.value = initialPromoId;
            promoIdField.value = initialPromoId;
            // set promo code display
            promoCodeDisplay.textContent = opt.text.split(' ')[0];
            updateTotal(initialPromoDiscount);
        }
    }
});
</script>
@endpush
@endsection
