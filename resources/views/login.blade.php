<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyMagz</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row min-vh-100 d-flex align-items-center">
            @if (Session::get('success'))
                {{-- Session::get -> mengambil with() yang di controller --}}
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            <!-- Bagian Form -->
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <a href="{{ route('home') }}" class="text-secondary mb-3 d-inline-block">
                    <i class="fa-solid fa-angle-left fa-lg fs-2 mb-5"></i>
                </a>

                <form method="POST" action="{{ route('login.auth') }}" class="p-4 p-md-5 w-100"
                    style="max-width: 400px;">
                    @csrf
                    <!-- Bagian Gambar -->
                    <div class="d-md-flex justify-content-center">
                        <img src="{{ asset('images/skymagz.png') }}" class="img-fluid w-50 mb-5" alt="">
                    </div>

                    <h4 class="mb-4">Masuk</h4>

                    <!-- Email input -->
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="mb-4">
                        <label class="form-label" for="form2Example1">Email address</label>
                        <input type="email" name="email" id="form2Example1"
                            class="form-control @error('email') is-invalid @enderror border-0 border-bottom" />
                    </div>

                    <!-- Password input -->
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="mb-4">
                        <label class="form-label" for="form2Example2">Password</label>
                        <input type="password" name="password" id="form2Example2"
                            class="form-control @error('password') is-invalid @enderror border-0 border-bottom" />
                    </div>

                    <!-- 2 column grid layout -->
                    <div class="row mb-4">
                        <div class="col text-end">
                            <a href="#!">Lupa Kata Sandi?</a>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary w-100 mb-4">Login</button>

                    <!-- Register buttons -->
                    <div class="text-center">
                        <p>Baru Di SkyMagz? <a href="{{route('sign_up.add')}}">Daftar</a></p>
                        <p class="text-muted">or sign up with:</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-secondary btn-floating">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button type="button" class="btn btn-secondary btn-floating">
                                <i class="fab fa-google"></i>
                            </button>
                            <button type="button" class="btn btn-secondary btn-floating">
                                <i class="fab fa-twitter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Bagian Gambar -->
            <div class="col-md-6 d-none d-md-flex justify-content-center">
                <img src="{{ asset('images/login.png') }}" class="img-fluid p-5" alt="">
            </div>
        </div>
    </div>

    <!-- MDB -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
</body>

</html>
