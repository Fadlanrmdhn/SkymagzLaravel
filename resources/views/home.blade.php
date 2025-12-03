@extends('templateUser.app')
@section('content')
    @if (Session::get('success'))
        {{-- Auth::user() : mengambil data user yang login --}}
        {{-- format : Auth::user()->column_di_fillable --}}
        <div class="alert alert-success w-100 alert-dismissible fade show alert-top-right" role="alert">
            {{ Session::get('success') }} <b>Selamat Datang!, {{ Auth::user()->name }}</b>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (Session::get('logout'))
        <div class="alert alert-warning alert-dismissible fade show alert-top-right" role="alert">
            {{ Session::get('logout') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- carousel --}}
    {{-- header --}}
    <header class=" text-center bg-image "
        style="
    background-image: url('{{ asset('images/Group 19.png') }}');
    background-size: cover;
    background-position: center;
      height: 50vh;
    ">
        <div class="mask d-flex justify-content-center align-items-center" style="background-color: rgba(0, 0, 0, 0.6);">
            <div class="container">
                <div id="carouselExampleCaptions" class="carousel slide d-flex justify-content-center align-items-center"
                    style="display: none; !important;" data-mdb-ride="carousel" data-mdb-carousel-init>
                    <div class="carousel-indicators">
                        <button type="button" data-mdb-target="#carouselExampleCaptions" data-mdb-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"
                            style="width: 10px; height: 10px; border-top: 0; border-bottom: 0; border-radius: 10rem; !important;"></button>
                        <button type="button" data-mdb-target="#carouselExampleCaptions" data-mdb-slide-to="1"
                            aria-label="Slide 2"
                            style="width: 10px; height: 10px; border-top: 0; border-bottom: 0; border-radius: 10rem; !important;"></button>
                        <button type="button" data-mdb-target="#carouselExampleCaptions" data-mdb-slide-to="2"
                            aria-label="Slide 3"
                            style="width: 10px; height: 10px; border-top: 0; border-bottom: 0; border-radius: 10rem; !important;"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('images/carousel1.png') }}" class=" w-100 rounded-6"
                                style="object-fit: cover; height: 300px;" alt="Wild Landscape" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5>First slide label</h5>
                                <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/carousel2.png') }}" class=" w-100 rounded-6"
                                style="object-fit: cover; height: 300px;" alt="Camera" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Second slide label</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/carousel3.png') }}" class=" w-100 rounded-6"
                                style="object-fit: cover; height: 300px;" alt="Exotic Fruits" />
                            <div class="carousel-caption d-none d-md-block text-black">
                                <h5>Mudah Dibaca Dimana Saja</h5>
                                <p>Dengan SkyMagz Semua Buku Dan Majalah Ada Ditangan Mu</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev h-25" style="margin-top: 8rem;" type="button"
                        data-mdb-target="#carouselExampleCaptions" data-mdb-slide="prev">
                        <span class="d-none d-md-block" aria-hidden="true"
                            style="margin-right: 13rem; color: black; background-color: white; border: solid 2px; border-radius: 5rem; padding: 1rem;"><i
                                class="fa-solid fa-angle-left"></i></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next h-25" style="margin-top: 8rem;" type="button"
                        data-mdb-target="#carouselExampleCaptions" data-mdb-slide="next">
                        <span class="d-none d-md-block" aria-hidden="true"
                            style="margin-left: 13rem; color: black; background-color: white; border: solid 2px; border-radius: 5rem; padding: 1rem;"><i
                                class="fa-solid fa-angle-right"></i></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    {{-- end carousel --}}

    {{-- content --}}
    <div class="container mt-5">
        <div class="d-flex justify-content-center">
            <div class="d-flex justify-content-center border border-4 border-primary w-25 pt-2"
                style="border-radius: 5rem;">
                <h5 class="text-dark fw-bold  align-items-center"><i
                        class="fa-solid fa-book-open mx-1 text-primary"></i>Kategori<i
                        class="fa-solid fa-book-open mx-1 text-primary"></i></h5>
            </div>
        </div>

        {{-- card kategori --}}
        <div class="mt-4 d-flex justify-content-center container flex-wrap gap-5 swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($categories as $category)
                    <a href="{{ route('category.books', $category->id) }}" class="gap-3 text-decoration-none mx-1">
                        <div class="card swiper-slide"
                            style="max-width: 285px; margin-bottom: 2rem; background-color: #ededed;">
                            <div class="row g-0">
                                <div class="col-md-8 d-flex align-items-center">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-primary">{{ $category->name }}</h6>
                                        <p class="card-text mb-1" style="font-size: small;">
                                            <b>Genre:</b> {{ $category->gender }}
                                        </p>
                                        <p class="card-text" style="font-size: small;">
                                            {{ Str::limit($category->description, 50, '...') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Tombol Navigasi -->
            <div class="swiper-button-next fs-bold text-dark bg-light p-4" style="border-radius: 5rem; border: solid 2px;">
            </div>
            <div class="swiper-button-prev fs-bold text-dark bg-light p-4"
                style="border-radius: 5rem; border: solid 2px;"></div>
        </div>
        {{-- end card kategori --}}


        {{-- banner --}}
        <div class="d-flex justify-content-center pt-5" style="padding-bottom: 5rem;">
            <div class="d-flex justify-content-center w-100 pt-2" style="border-radius: 5rem;">
                <img src="{{ asset('images/Group 38.png') }}" class=" w-100 rounded-6" style="object-fit: cover; "
                    alt="Camera" />
            </div>
        </div>

        {{-- card buku --}}
        <div class="mt-2 d-flex justify-content-between m-0">
            <div class="d-flex gap-3 mt-4">

                <!-- Kartu kiri -->
                <div class="rounded-4 shadow-sm d-flex flex-column align-items-center justify-content-start">
                    <button class="w-75 rounded-6 border-0 fw-bold" style="background-color: yellow">Buku</button>
                    <img src="{{ asset('images/Buku.png') }}" alt="Orang"
                        style="width: 20rem; border-radius: 1rem; margin-top: 0.5rem; max-width:100%; height:auto;">
                </div>
                <div class="d-flex align-items-start" style="white-space: nowrap;">
                    <a href="" class="text-secondary fw-bold mt-1">Lihat Semua</a>
                </div>

                <!-- Kartu kanan (rapih, ukuran seragam, tidak terlalu panjang) -->
                <div class="d-flex gap-2 rounded-4 swiper mySwiper w-100"
                    style="margin-left: -12rem; margin-top: 2.5rem; ">
                    <div class="swiper-wrapper" style="align-items: flex-start;">
                        @foreach ($books as $item)
                            <a href="{{ route('detail.show', $item->id) }}"
                                class="card shadow-sm text-decoration-none swiper-slide d-flex flex-column align-items-center"
                                style="min-width: 10rem; max-width: 10rem; box-sizing: border-box; border: solid 3px;">
                                <img src="{{ asset('storage/' . $item['cover']) }}" alt="{{ $item['title'] }}"
                                    class="card-img-top"
                                    style="width: 7rem; height: 10rem; object-fit: cover; margin: 0.75rem auto 0; border-radius: 0.5rem; max-width:100%;" />
                                <div class="p-2 w-100">
                                    <div class="d-flex justify-content-start gap-1 mb-1">
                                        <span class="badge bg-primary small">Digital</span>
                                        <span class="badge bg-success small">PDF</span>
                                    </div>
                                    <p class="mb-0 fw-light mt-1 text-truncate" style="font-size: 0.85rem;">
                                        {{ $item->short_title }}
                                    </p>
                                    <p class="mb-1 text-truncate" style="font-size: 0.85rem; font-weight: 500;">
                                        {{ $item->short_author }}
                                    </p>
                                    <div style="font-size: 0.9rem; font-weight: 700;">
                                        Rp. {{ number_format($item['price'], 0, ',', ',') }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <!-- Tombol Navigasi -->
                    <div class="swiper-button-next fs-bold text-dark bg-light p-3"
                        style="border-radius: 5rem; border: solid 2px;"></div>
                    <div class="swiper-button-prev fs-bold text-dark bg-light p-3"
                        style="border-radius: 5rem; border: solid 2px;"></div>
                </div>
            </div>


        </div>
        {{-- end card buku --}}

        {{-- card majalah --}}
        <div class="mt-5 d-flex justify-content-between">
            <div class="d-flex align-items-start gap-3 mt-4 flex-wrap">

                <!-- Kartu kiri -->
                <div class="rounded-4 shadow-sm d-flex flex-column align-items-center justify-content-start">
                    <button class="w-75 rounded-6 border-0 fw-bold" style="background-color: yellow">Majalah</button>
                    <img src="{{ asset('images/Majalah.png') }}" alt="Orang"
                        style="width: 14rem; border-radius: 1rem; margin-top: 0.5rem;">
                </div>

                <!-- Kartu kanan -->
                <div class="d-flex gap-3 flex-wrap border border-2 border-primary rounded-4 swiper mySwiper"
                    style="margin-left: -3rem; margin-top: 2.5rem;">
                    <div class="swiper-wrapper">
                        @foreach ($magazines as $item)
                            <a href="{{ route('detail.show', $item->id) }}"
                                class="card shadow-sm border-primary border-1 rounded-4 text-decoration-none swiper-slide">
                                <img src="{{ asset('storage/' . $item['cover']) }}" alt="{{ $item['title'] }}"
                                    class="card-img-top p-2 d-flex justify-content-center"
                                    style="width: 8rem; height: 11rem; margin:auto;" />
                                <div class="p-2">
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-primary">Digital</span>
                                        <span class="badge bg-success">PDF</span>
                                    </div>
                                    <p class="mb-0 fw-light mt-1" style="font-size: small;">{{ $item->short_title }}</p>
                                    <p class="mb-1" style="font-size: small; font-weight: 500;">
                                        {{ $item->short_author }}
                                    </p>
                                    <div style="font-size: small; font-weight: 700;">
                                        Rp. {{ number_format($item['price'], 0, ',', ',') }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <!-- Tombol Navigasi -->
                    <div class="swiper-button-next fs-bold text-dark bg-light p-4"
                        style="border-radius: 5rem; border: solid 2px;">
                    </div>
                    <div class="swiper-button-prev fs-bold text-dark bg-light p-4"
                        style="border-radius: 5rem; border: solid 2px;">
                    </div>
                </div>
            </div>
            <a href="" class="text-secondary fw-bold mt-4">Lihat Semua</a>
        </div>
        {{-- end card majalah --}}


    </div>
@endsection
