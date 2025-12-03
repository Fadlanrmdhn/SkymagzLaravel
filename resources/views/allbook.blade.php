@extends('templateUser.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Semua Buku</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if($books->isEmpty())
        <div class="alert alert-info">Belum ada buku tersedia.</div>
    @else
        <div class="row g-3">
            @foreach($books as $book)
                <div class="col-6 col-md-3">
                    <a href="{{ route('detail.show', $book->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('storage/' . $book->cover) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $book->title }}">
                            <div class="card-body p-2">
                                <p class="mb-1 small text-truncate">{{ $book->short_title }}</p>
                                <p class="mb-1 small text-truncate text-muted">{{ $book->short_author }}</p>
                                <div class="fw-bold">Rp {{ number_format($book->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
