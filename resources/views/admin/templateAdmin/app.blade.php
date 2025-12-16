<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin SkyMagz</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- CDN Jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

</head>

<body>
    <!-- Sidebar -->
    <div class="d-flex">
        <div class="d-flex flex-column p-3 bg-dark text-white vh-100"
            style="width: 250px; position: fixed; top: 0; left: 0;">
            <a href="#" class="d-flex mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">SkyMagz Admin</span>
            </a>
            <hr>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active text-white">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>

                <!-- Dropdown collapse -->
                <li>
                    <a class="nav-link text-white bg-secondary d-flex justify-content-between align-items-center mt-2"
                        data-bs-toggle="collapse" href="#dataMasterCollapse" role="button" aria-expanded="false"
                        aria-controls="dataMasterCollapse">
                        <span><i class="fas fa-database me-2"></i> Data Master</span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>

                    <div class="collapse ps-3" id="dataMasterCollapse">
                        <ul class="nav flex-column">
                            <li><a href="{{ route('admin.magazines.index') }}" class="nav-link text-dark bg-light">Magazines</a></li>
                            <li><a href="{{ route('admin.books.index') }}" class="nav-link text-dark bg-light">Books</a></li>
                            <li><a href="{{ route('admin.categories.index') }}" class="nav-link text-dark bg-light">Category</a></li>
                            <li><a href="{{ route('admin.promos.index') }}" class="nav-link text-dark bg-light">Promos</a></li>
                            <li><a href="{{ route('admin.orders.index') }}" class="nav-link text-dark bg-light">Order</a></li>
                            <li><a href="{{ route('admin.users.index') }}" class="nav-link text-dark bg-light">Users</a></li>
                        </ul>
                    </div>
                </li>
            </ul>

            <hr>
            <div>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100">Logout</a>
            </div>
        </div>


        <!-- Content -->
        <div class="flex-grow-1" style="margin-left: 250px; padding: 20px;">
            @yield('content')
        </div>
    </div>

    <!--MDB Bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"
        integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous">
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- DataTable CDN --}}
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    {{-- CDN ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    </script>

    {{-- menyimpan konten dinamis bagian JS --}}
    @stack('script')
</body>

</html>
