@extends('admin.templateAdmin.app')

@section('content')
    <div class="mt-5 w-75 d-block m-auto">
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit</a></li>
                    </ol>
                </nav>
            </div>
        </nav>
    </div>

    <div class="card w-75 mx-auto my-3 p-4">
        <h5 class="text-center my-3">Edit Data Kategori</h5>
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input
                    type="text"
                    class="form-control @error('genre') is-invalid @enderror"
                    id="genre"
                    name="genre"
                    value="{{ old('genre', $category->genre) }}">
                @error('genre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea
                    class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description"
                    rows="4">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
