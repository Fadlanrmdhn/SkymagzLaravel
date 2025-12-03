@extends('admin.templateAdmin.app')
@section('content')
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
                <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
                <a href="{{ route('admin.users.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Data</a>
            </div>
            <table class="table table-bordered table-hover" id="usersTable">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @if ($user['role'] == 'admin')
                                    <span class="badge bg-success">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
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
            $('#usersTable').DataTable({
                processing: true,
                //data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                //routing menuju fungsi yang memproses data untuk datatable
                ajax: "{{ route('admin.users.datatables') }}",
                //urutan column (td), pastikan urutan sesuai th
                //data: 'nama' -> nama diambil dari rawColumn jika addColumn, atau field dari model fillable
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'name', name: 'name',},
                    { data: 'email', name: 'email',},
                    { data: 'role', name: 'role',},
                    { data: 'action', name: 'action'},
                ]
            });
        });
    </script>
@endpush
