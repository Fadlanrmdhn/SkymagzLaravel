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
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.categories.index') }}">Category</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Trash</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="table-responsive">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif

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
                    @foreach ($categoryTrash as $key => $category)
                        <tr class="text-center">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->gender }}</td>
                            <td class="text-center d-flex justify-content-center gap-2">
                                <form action="{{ route('admin.categories.restore', $category['id']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Kembalikan</button>
                                </form>
                                <form action="{{ route('admin.categories.delete_permanent', $category['id']) }}" method="POST">
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
