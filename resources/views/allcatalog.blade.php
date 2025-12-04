@extends('templateUser.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Semua Katalog</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if(isset($query) && $query)
        <div class="alert alert-info">Hasil pencarian untuk: <strong>{{ $query }}</strong></div>
    @endif

    @if($magazines->isEmpty())
        <div class="alert alert-info">Belum ada katalog tersedia.</div>
    @else
        <div class="row g-3">
            @foreach($magazines as $magazine)
                <div class="col-6 col-md-3">
                    <a href="{{ route('detail.show', $magazine->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('storage/' . $magazine->cover) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $magazine->title }}">
                            <div class="card-body p-2">
                                <p class="mb-1 small text-truncate">{{ $magazine->short_title }}</p>
                                <p class="mb-1 small text-truncate text-muted">{{ $magazine->short_author }}</p>
                                <div class="fw-bold">Rp {{ number_format($magazine->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
