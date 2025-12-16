@extends('admin.templateAdmin.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show alert-top-right" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="p-2">
        <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0">User Management</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="table-responsive">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            <div class="d-flex justify-content-end mb-3 mt-4">
                <a href="{{ route('admin.promos.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
                <a href="{{ route('admin.promos.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
                <a href="{{ route('admin.promos.create') }}" class="btn btn-success">Tambah Data</a>
            </div>
            <table class="table table-bordered table-hover" id="promosTable">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Promo Code</th>
                        <th>Discount</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($promos as $key => $promo)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $promo->promo_code }}</td>
                            <td>
                                @if ($promo['type'] == 'rupiah')
                                    <span>Rp. {{ number_format($promo['discount'], 0, ',', ',') }}</span>
                                @else
                                    <span>{{ $promo['discount'] }} %</span>
                                @endif
                            </td>

                            <td>{{ $promo->type }}</td>

                            <td>
                                @if ($promo['activated'] == 1)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>

                            <td class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.promos.edit', $promo['id']) }}" class="btn btn-info">Edit</a>
                                <form action="{{ route('admin.promos.delete', $promo['id']) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Hapus</button>
                                </form>
                                {{-- jika activated true, munculkan opsi untuk non-aktif film --}}
                                @if ($promo['activated'] == 1)
                                    <form action="{{ route('admin.promos.activated', $promo['id']) }}" method="post">

                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-warning">Non-Aktif</button>
                                    </form>
                                @endif
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
            $('#promosTable').DataTable({
                processing: true,
                //data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                //routing menuju fungsi yang memproses data untuk datatable
                ajax: "{{ route('admin.promos.datatables') }}",
                //urutan column (td), pastikan urutan sesuai th
                //data: 'nama' -> nama diambil dari rawColumn jika addColumn, atau field dari model fillable
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'promo_code',
                        name: 'promo_code'
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                    },
                    {
                        data: 'type',
                        name: 'type',
                    },
                    {
                        data: 'activated',
                        name: 'activated',
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
