@extends('admin.templateAdmin.app')

@section('content')
    <div class="p-2">
        <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0">Order Management</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>

            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
            </div>
        </nav>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3 mt-4">
            <a href="{{ route('admin.orders.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.orders.trash') }}" class="btn btn-secondary">Data Sampah</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="ordersTable">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>#</th>
                        <th>User</th>
                        <th>Subtotal</th>
                        <th>Promo</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody class="align-middle">
                    @foreach ($orders as $order)
                        @php
                            $subtotal = 0;
                            foreach ($order->orderDetails as $d) {
                                $subtotal += $d->total_price;
                            }
                        @endphp

                        <tr>
                            <td class="text-center">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->user->name ?? '—' }}</td>
                            <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="text-end">{{ $order->promo->promo_code ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>

                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $('#ordersTable').DataTable({
                processing: true,
                //data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                //routing menuju fungsi yang memproses data untuk datatable
                ajax: "{{ route('admin.orders.datatables') }}",
                //urutan column (td), pastikan urutan sesuai th
                //data: 'nama' -> nama diambil dari rawColumn jika addColumn, atau field dari model fillable
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                    },
                    {
                        data: 'promo_id',
                        name: 'promo_id',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'order_date',
                        name: 'order_date',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
