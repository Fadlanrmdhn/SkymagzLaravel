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
            <!-- Bagian Form -->
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <a href="{{ route('home') }}" class="text-dark mb-3 d-inline-block">
                    <i class="fa-solid fa-angle-left fa-lg fs-2 mb-5"></i>
                </a>
                <form method="POST" action="{{ route('sign_up.add') }}" class="p-4 p-md-5 w-100"
                    style="max-width: 400px;">
                    @csrf
                    <!-- Bagian Gambar -->
                    <div class="d-md-flex justify-content-center">
                        <img src="{{ asset('images/skymagz.png') }}" class="img-fluid w-50 mb-5" alt="">
                    </div>

                    <h4 class="mb-4">Buat Akun</h4>

                    <!-- nama input -->
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="mb-5 ">
                        <label class="form-label" for="form2Example2">Nama</label>
                        <input type="name" id="form2Example2"
                            class="form-control border-0 border-bottom @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" />
                    </div>

                    <!-- Email input -->
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="mb-4">
                        <label class="form-label" for="form2Example1">Email address</label>
                        <input type="email" id="form2Example1"
                            class="form-control border-0 border-bottom @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}">
                    </div>

                    <!-- Password input -->
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="mb-4">
                        <label class="form-label" for="form2Example2">Password</label>
                        <input type="password" id="form2Example2"
                            class="form-control border-0 border-bottom @error('password') is-invalid @enderror"
                            name="password" value="{{ old('password') }}" />
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary w-100 mb-4">Sign in</button>

                    <!-- Register buttons -->
                    <div class="text-center">
                        <p>Sudah punya akun SkyMagz digital? <a href="#!">Masuk Kirim ulang email verifikasi</a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Bagian Gambar -->
            <div class="col-md-6 d-none d-md-flex justify-content-center">
                <img src="{{ asset('images/signup.png') }}" class="img-fluid w-75" alt="">
            </div>
        </div>
    </div>

    <!-- MDB -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
</body>

</html>
