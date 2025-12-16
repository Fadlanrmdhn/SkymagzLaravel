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
        <div class="row gap-3">
            @foreach($magazines as $magazine)
                <div class="card shadow-sm text-decoration-none d-flex flex-column align-items-center"
                        style="min-width: 5rem; max-width: 10rem; box-sizing: border-box;">
                        <a href="{{ route('detail.show', $magazine->id) }}"
                            class="card shadow-sm text-decoration-none swiper-slide d-flex flex-column align-items-center"
                            style="min-width: 10rem; max-width: 10rem; box-sizing: border-box; border: solid 3px;">
                            <img src="{{ asset('storage/' . $magazine['cover']) }}" alt="{{ $magazine['title'] }}"
                                class="card-img-top"
                                style="width: 7rem; height: 10rem; object-fit: cover; margin: 0.75rem auto 0; border-radius: 0.5rem; max-width:100%;" />
                            <div class="p-2 w-100">
                                <div class="d-flex justify-content-start gap-1 mb-1">
                                    <span class="badge bg-primary small">Digital</span>
                                    <span class="badge bg-success small">PDF</span>
                                </div>
                                <p class="mb-0 fw-light mt-1 text-truncate" style="font-size: 0.85rem;">
                                    {{ $magazine->short_title }}
                                </p>
                                <p class="mb-1 text-truncate" style="font-size: 0.85rem; font-weight: 500;">
                                    {{ $magazine->short_author }}
                                </p>
                                <div style="font-size: 0.9rem; font-weight: 700;">
                                    Rp. {{ number_format($magazine['price'], 0, ',', ',') }}
                                </div>
                            </div>
                        </a>
                    </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
