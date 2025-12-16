@extends('admin.templateAdmin.app')

@section('content')
<div class="p-2">
    <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
        <div class="container-fluid">
            <span class="navbar-brand mb-0">Order #{{ str_pad($order->id,6,'0', STR_PAD_LEFT) }}</span>
            <div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <h5>Informasi Pemesan</h5>
            <p><strong>Nama:</strong> {{ $order->user->name ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
            <p><strong>Tanggal:</strong> {{ $order->order_date->format('d M Y H:i') }}</p>
            <p><strong>Status:</strong> <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span></p>

            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="row g-2">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <label class="form-label">Ubah Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-end">
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Detail Pesanan</h5>
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
                    @php $subtotal = 0; @endphp
                    @foreach($order->orderDetails as $detail)
                        @php $subtotal += $detail->total_price; @endphp
                        <tr>
                            <td>{{ $detail->magazine->title }}</td>
                            <td class="text-end">{{ $detail->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($detail->magazine->price,0,',','.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->total_price,0,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Subtotal:</td>
                        <td class="text-end">Rp {{ number_format($subtotal,0,',','.') }}</td>
                    </tr>
                    @if($order->promo)
                        <tr>
                            <td colspan="3" class="text-end">Promo:</td>
                            <td class="text-end">{{ $order->promo->promo_code }}</td>
                        </tr>
                    @endif
                    @php $discount = $subtotal - $order->total_amount; @endphp
                    @if($discount > 0)
                        <tr>
                            <td colspan="3" class="text-end text-danger">Diskon:</td>
                            <td class="text-end text-danger">-Rp {{ number_format($discount,0,',','.') }}</td>
                        </tr>
                    @endif
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Total:</td>
                        <td class="text-end">Rp {{ number_format($order->total_amount,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus order ini?')" class="mt-3">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Hapus Order</button>
    </form>
</div>
@endsection
