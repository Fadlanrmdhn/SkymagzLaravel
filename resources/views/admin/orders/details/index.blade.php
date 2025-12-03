@extends('admin.templateAdmin.app')

@section('content')
<div class="p-2">
    <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
        <div class="container-fluid">
            <span class="navbar-brand mb-0">Order #{{ str_pad($order->id,6,'0', STR_PAD_LEFT) }} - Details</span>
            <div>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $detail)
                        <tr>
                            <td>{{ $detail->magazine->title }}</td>
                            <td class="text-end">{{ $detail->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($detail->magazine->price,0,',','.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->total_price,0,',','.') }}</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editDetailModal{{ $detail->id }}">Edit</a>
                                <form action="{{ route('admin.orders.details.destroy', $detail->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus detail ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="editDetailModal{{ $detail->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.orders.details.update', $detail->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Detail</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" name="quantity" class="form-control" value="{{ $detail->quantity }}" min="1" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Subtotal (Rp)</label>
                                                <input type="number" name="total_price" class="form-control" value="{{ $detail->total_price }}" min="0" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
