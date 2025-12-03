@extends('admin.templateAdmin.app')

@section('content')
    <div class="p-2">
        <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0">Recycle Bin Magazine Management</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Magazine</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Recycle Bin Magazine</li>
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
                        <th>No</th>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($magazineTrash as $key => $item)
                        <tr class="text-center">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($item['cover'])
                                    <img src="{{ asset('storage/' . $item['cover']) }}" width="120"
                                        class="img-thumbnail">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->author }}</td>
                            <td>{{ $item->publisher }}</td>
                            <td>Rp. {{ number_format($item['price'], 0, ',', ',') }}</td>
                            <td class="text-center d-flex justify-content-center gap-2">
                                <form action="{{ route('admin.magazines.restore', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Kembalikan</button>
                                </form>
                                <form action="{{ route('admin.magazines.delete_permanent', $item['id']) }}" method="POST">
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
    @endsection
