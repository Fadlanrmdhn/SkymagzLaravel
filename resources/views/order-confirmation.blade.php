@extends('templateUser.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Message -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">
                    <i class="bi bi-check-circle"></i> Pesanan Berhasil Dibuat!
                </h4>
                <p class="mb-0">Silakan lakukan pembayaran untuk melanjutkan proses pengiriman.</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Order Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Konfirmasi Pesanan #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h5>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Informasi Pesanan</h6>
                            <p class="mb-1"><strong>Nomor Pesanan:</strong> #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="mb-1"><strong>Tanggal Pesanan:</strong> {{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y H:i') }}</p>
                            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">{{ ucfirst($order->status) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Data Pembeli</h6>
                            <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Items -->
                    <h6 class="fw-bold mb-3">Detail Pesanan</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Buku</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                    <tr>
                                        <td>
                                            <strong>{{ $detail->magazine->title }}</strong>
                                            <div class="text-muted small">{{ $detail->magazine->author }}</div>
                                        </td>
                                        <td class="text-end">{{ $detail->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($detail->magazine->price, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @php
                                    $subtotal = 0;
                                    foreach($order->orderDetails as $detail) {
                                        $subtotal += $detail->total_price;
                                    }
                                    // Prefer stored promo_amount when available, fallback to computed difference
                                    $discount = isset($order->promo_amount) ? $order->promo_amount : ($subtotal - $order->total_amount);
                                @endphp
                                <tr class="border-top">
                                    <td colspan="3" class="text-end">Subtotal:</td>
                                    <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->promo)
                                    <tr>
                                        <td colspan="3" class="text-end">Promo:</td>
                                        <td class="text-end">{{ $order->promo->promo_code }}</td>
                                    </tr>
                                @endif
                                @if($discount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end text-danger">Diskon:</td>
                                        <td class="text-end text-danger fw-bold">-Rp {{ number_format($discount, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="fw-bold border-top border-3">
                                    <td colspan="3" class="text-end">Total Pembayaran:</td>
                                    <td class="text-end text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr>

                    <!-- Payment Instructions -->
                    <div class="alert alert-info mb-0">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-info-circle"></i> Instruksi Pembayaran
                        </h6>
                        <ul class="mb-0">
                            <li>Silakan lakukan pembayaran sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                            <li>Pesanan akan diproses setelah pembayaran dikonfirmasi</li>
                            <li>Untuk buku digital (ebook), akses akan tersedia setelah pembayaran sukses</li>
                            <li>Simpan nomor pesanan Anda untuk referensi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="bi bi-house"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-pdf"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
