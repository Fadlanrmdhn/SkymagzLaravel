@extends('admin.templateAdmin.app')

@section('content')
    <div class="p-2">
        <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0">Create New Magazine</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.magazines.index') }}">Magazine</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="card p-4 shadow-sm">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            <form action="{{ route('admin.magazines.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="title" class="form-label">Judul Majalah</label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="author" class="form-label">Pembuat Majalah</label>
                        <input type="text" name="author" id="author"
                            class="form-control @error('author') is-invalid @enderror">
                        @error('author')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="publisher" class="form-label">Penerbit Majalah</label>
                        <input type="text" name="publisher" id="publisher"
                            class="form-control @error('publisher') is-invalid @enderror">
                        @error('publisher')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="type" class="form-label">Tipe</label>
                        <select name="type" id="type" class="form-control">
                            <option value="majalah" {{ old('type') == 'majalah' ? 'selected' : '' }}>Majalah</option>
                        </select>
                        @error('type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="mb-3">
                        <label for="release_date" class="form-label">Tanggal Release Majalah</label>
                        <input type="date" name="release_date" id="release_date"
                            class="form-control  @error('release_date') is-invalid @enderror">
                        @error('release_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="cover" class="form-label">Cover Majalah</label>
                        <input type="file" name="cover" id="cover"
                            class="form-control  @error('cover') is-invalid @enderror">
                        @error('cover')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga Majalah</label>
                        <input type="number" name="price" id="price"
                            class="form-control  @error('cover') is-invalid @enderror">
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" rows="10"
                            class="form-control @error('description') is-invalid @enderror"></textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>
@endsection
