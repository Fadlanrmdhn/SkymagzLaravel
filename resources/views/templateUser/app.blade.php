<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SkyMagz</title>
    <!-- favivon.ico -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<style>
    * {
        font-family: "Poppins", sans-serif;
    }
</style>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <!-- Container wrapper -->
        <div class="container py-2">
            <!-- Navbar brand -->
            <a class="navbar-brand me-2" href="{{ route('home') }}">
                <img src="{{ asset('images/skymagz-.png') }}" height="30" alt="SkyMagz logo" loading="lazy"
                    style="margin-top: -1px;" />
                <h5 class="me-auto mb-2 mb-lg-0 fw-bold" style="font-family: Inknut Antiqua;">SkyMagz</h5>
            </a>

            <!-- Toggle button -->
            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarButtonsExample"
                aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <!-- Left links -->
                <div class="navbar-nav me-auto mb-2 mb-lg-0">
                    <div class="container-fluid">
                        <ul class="navbar-nav">
                            <!-- Dropdown -->
                            <li class="nav-item dropdown">
                                <a data-mdb-dropdown-init class="nav-link" href="#" id="navbarDropdownMenuLink"
                                    role="button" aria-expanded="false">
                                    Kategori <i class="fa-solid fa-angle-down px-2"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('allbook') }}">Buku</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('allmagazine') }}">Majalah</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Left links -->

                {{-- search center --}}
                <form action="{{ route('catalog') }}" method="GET" class="d-flex w-50 me-auto mb-2 mb-lg-0">
                    <div class="input-group rounded">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 13px 0 0 13px; cursor: pointer;">
                            <button type="submit" class="btn btn-link p-0 m-0 text-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0" style="border-radius: 0 13px 13px 0;" placeholder="Search books or magazines..." value="{{ request('q') }}">
                    </div>
                </form>
                {{-- search center --}}

                {{-- cart shooping --}}
                <span class="me-4">
                    <a href="{{ route('cart') }}"><i class="fa-solid fa-cart-shopping"></i></a>
                </span>
                {{-- cart shooping --}}

                <div class="d-flex align-items-center">
                    @if (Auth::check())
                        <a href="{{ route('logout') }}" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                    @else
                        <span class="px-2 fs-3">|</span>
                        <a href="{{ route('login') }}" data-mdb-ripple-init type="button"
                            class="btn btn-outline-primary mx-2 rounded-6" data-mdb-ripple-init
                            data-mdb-ripple-color="dark">
                            Login
                        </a>
                        <a href="{{ route('signUp') }}" data-mdb-ripple-init type="button"
                            class="btn btn-primary me-3 rounded-6">
                            Sign up
                        </a>
                    @endif
                </div>
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <section class="navbar navbar-expand-lg bg-body border-top border-3">
        <div class="container-fluid" style="margin-top: -12px; margin-bottom: -12px;">
            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarExample01"
                aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarExample01">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" aria-current="page" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('allbook') }}">Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('allmagazine') }}">Majalah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('catalog') }}">Katalog</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <!-- Navbar -->

    @yield('content')
    <!-- MDB -->

    <section class="mt-5 border-top border-2 ">
        <!-- Footer -->
        <footer class="bg-body-tertiary">
            <div class="p-1 w-100 container d-flex justify-content-between" style="">
                <img src="{{ asset('images/skymagz.png') }}" alt="" class=""
                    style="width: 7rem; !important;">
                <div class="text-center">
                    <p class="fw-bold text-secondary" style="margin: 1rem; font-size: small; !important;">Toko Buku &
                        Majalah Digital
                        Terlengkap dan
                        Terpercaya</p>
                </div>
            </div>

            <!-- Copyright -->
            <div class="p-1" style="background-color: #f5f5f5;">
                <div class="container p-1 border-top border-1 d-flex justify-content-between">
                    <p class="text-body" style="margin: 1rem; font-size: smaller;" href="">© 2025 SkyMagz
                        Magazine Digital</p>
                    <div class="d-flex justify-content-center" style="margin: 1rem; font-size: smaller;">
                        <a href=""><i class="fa-brands fa-facebook fa-lg text-body mx-2"></i></a>
                        <a href=""><i class="fa-brands fa-twitter fa-lg text-body mx-2"></i></a>
                        <a href=""><i class="fa-brands fa-instagram fa-lg text-body mx-2"></i></a>
                        <a href=""><i class="fa-brands fa-youtube fa-lg text-body mx-2"></i></a>
                    </div>
                </div>

            </div>
            <!-- Copyright -->
        </footer>
        <!-- Footer -->
    </section>

    <!-- mdb Bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
<script>
    const swiper = new Swiper(".mySwiper", {
        slidesPerView: "auto",
        spaceBetween: 20,
        freeMode: true,

        // Auto Slide
        autoplay: {
            delay: 2500, //slide otomatis jalan
            disableOnInteraction: false, //bikin autoplay tetep jalan meskipun user klik tombol navigasi/geser manual.
            pauseOnMouseEnter: true, // berhenti kalau kursor hover
        },

        // Tombol Navigasi
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>


{{-- sweetalert 2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if (session('failed'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('failed') }}"
        });
    @endif

    @if (session('logout'))
        Swal.fire({
            icon: 'warning',
            title: 'Logout',
            text: "{{ session('logout') }}"
        });
    @endif
</script>

</span>
