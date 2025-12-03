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
                <span class="navbar-brand mb-0">Category Management</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="table-responsive">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif

            <div class="d-flex justify-content-end mb-3 mt-4">
                <a href="{{ route('admin.categories.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
                <a href="" class="btn btn-secondary me-2">Export (.xlsx)</a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Tambah Data</a>
            </div>

            <table class="table table-bordered table-hover" id="categoriesTable">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Genre</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($categories as $key => $category)
                        <tr class="text-center">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->gender }}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
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
        $(function(){
            $('#categoriesTable').DataTable({
                processing: true,
                //data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                //routing menuju fungsi yang memproses data untuk datatable
                ajax: "{{ route('admin.categories.datatables') }}",
                //urutan column (td), pastikan urutan sesuai th
                //data: 'nama' -> nama diambil dari rawColumn jika addColumn, atau field dari model fillable
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'name', name: 'name',},
                    { data: 'gender', name: 'gender',},
                    { data: 'action', name: 'action'},
                ]
            });
        });
    </script>
@endpush
