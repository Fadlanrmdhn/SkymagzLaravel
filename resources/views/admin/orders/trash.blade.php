@extends('admin.templateAdmin.app')

@section('content')
<div class="p-2">
    <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
        <div class="container-fluid">
            <span class="navbar-brand mb-0">Recycle Bin Order Management</span>
            <div class="d-flex align-items-center">
                <span>Admin SkyMagz</span>
            </div>
        </div>

        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Recycle Bin Orders</li>
                </ol>
            </nav>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>User</th>
                    <th>Subtotal</th>
                    <th>Promo</th>
                    <th>Status</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody class="align-middle">
                @foreach($orders as $order)
                    @php
                        $subtotal = 0;
                        foreach($order->orderDetails as $d) {
                            $subtotal += $d->total_price;
                        }
                    @endphp

                    <tr>
                        <td class="text-center">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->user->name ?? '—' }}</td>
                        <td class="text-end">Rp {{ number_format($subtotal,0,',','.') }}</td>
                        <td class="text-end">{{ $order->promo->promo_code ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>{{ $order->deleted_at->format('d M Y H:i') }}</td>

                        <td class="text-center d-flex justify-content-center gap-2">
                            <form action="{{ route('admin.orders.restore', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Kembalikan</button>
                            </form>
                            <form action="{{ route('admin.orders.delete_permanent', $order->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
