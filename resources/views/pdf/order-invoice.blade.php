<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-title {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .header-left {
            flex: 1;
        }

        .header-right {
            text-align: right;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 14px;
            color: #666;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        table thead {
            background-color: #f8f9fa;
            border-top: 2px solid #007bff;
            border-bottom: 2px solid #007bff;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table tr:last-child td {
            border-bottom: 2px solid #007bff;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            font-size: 13px;
        }

        .summary-box {
            width: 250px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #007bff;
            border-bottom: 2px solid #007bff;
            padding: 12px 0;
            color: #007bff;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            background-color: #ffc107;
            color: #000;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .section-2col {
            display: flex;
            gap: 40px;
        }

        .col {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-title">SkyMagz</div>
            <div class="header-info">
                <div class="header-left">
                    <p style="font-size: 12px; color: #666;">Platform Penjualan Buku & Majalah Digital</p>
                </div>
                <div class="header-right">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="section section-2col">
            <div class="col">
                <div class="section-title">Informasi Pembeli</div>
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value">{{ $order->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">User ID:</span>
                    <span class="info-value">{{ $order->user->id }}</span>
                </div>
            </div>
            <div class="col">
                <div class="section-title">Informasi Pesanan</div>
                <div class="info-row">
                    <span class="info-label">Tanggal:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($order->order_date)->format('H:i:s') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value"><span class="status-badge">{{ strtoupper($order->status) }}</span></span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="section">
            <div class="section-title">Detail Pesanan</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;">Buku</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 20%; text-align: right;">Harga Satuan</th>
                        <th style="width: 25%; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $detail)
                        <tr>
                            <td>
                                <strong>{{ $detail->magazine->title }}</strong><br>
                                <span style="color: #666; font-size: 11px;">Penulis: {{ $detail->magazine->author }}</span>
                            </td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($detail->magazine->price, 0, ',', '.') }}</td>
                            <td class="text-right"><strong>Rp {{ number_format($detail->total_price, 0, ',', '.') }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-box">
                    @php
                        $subtotal = 0;
                        foreach($order->orderDetails as $d) {
                            $subtotal += $d->total_price;
                        }
                        $discount = $subtotal - $order->total_amount;
                    @endphp
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->promo)
                        <div class="summary-row">
                            <span>Promo:</span>
                            <span>{{ $order->promo->promo_code }}</span>
                        </div>
                    @endif
                    @if($discount > 0)
                        <div class="summary-row">
                            <span>Diskon:</span>
                            <span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="summary-row">
                        <span>Pajak (0%):</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Pembayaran:</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="section">
            <div class="section-title">Catatan Penting</div>
            <ul style="margin-left: 20px; font-size: 12px; color: #666;">
                <li>Terima kasih atas pembelian Anda!</li>
                <li>Untuk buku digital (ebook), akses akan tersedia setelah pembayaran dikonfirmasi</li>
                <li>Simpan invoice ini sebagai bukti pembayaran</li>
                <li>Hubungi customer service jika ada pertanyaan</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} SkyMagz. Semua hak cipta dilindungi.</p>
            <p>Invoice ini dicetak pada {{ date('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
