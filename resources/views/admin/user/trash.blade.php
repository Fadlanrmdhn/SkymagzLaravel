@extends('admin.templateAdmin.app')

@section('content')
    <div class="p-2">
        <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0">Recycle Bin User Management</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Recycle Bin User</li>
                    </ol>
                </nav>
            </div>
        </nav>
        <div class="table-responsive">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            <table class="table table-bordered table-hover">
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
                    @foreach ($userTrash as $key => $user)
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
                            <td class="text-center d-flex justify-content-center gap-2">
                                <form action="{{ route('admin.users.restore', $user['id']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Kembalikan</button>
                                </form>
                                <form action="{{ route('admin.users.delete_permanent', $user['id']) }}" method="POST">
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
