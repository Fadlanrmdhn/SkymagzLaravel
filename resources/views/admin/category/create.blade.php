@extends('admin.templateAdmin.app')

@section('content')
    <div class="mt-5 w-75 d-block m-auto">
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif

        <nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Category</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </nav>
    </div>

    <div class="card w-75 mx-auto my-3 p-4 shadow-sm">
        <h5 class="text-center my-3">Buat Data Kategori</h5>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name"
                       name="name"
                       value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Genre --}}
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text"
                       class="form-control @error('genre') is-invalid @enderror"
                       id="gender"
                       name="gender"
                       value="{{ old('gender') }}">
                @error('genre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description"
                          name="description"
                          rows="4"
                          placeholder="Tulis deskripsi kategori...">{{ old('description') }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Buat</button>
        </form>
    </div>
@endsection
