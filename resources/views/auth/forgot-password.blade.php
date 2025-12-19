<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lupa Password | Sistem Inventory RS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css" crossorigin="anonymous" />
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="link-dark text-decoration-none">
                    {{-- Ganti logo sesuai asetmu --}}
                    <img src="{{ asset('dist/assets/img/logoKU.png') }}" alt="Logo" style="height: 50px; margin-bottom: 10px;"><br>
                    <h1 class="mb-0 fs-3"><b>Sistem</b> Inventory</h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Lupa password? Masukkan email akun Anda. Kami akan mengirimkan notifikasi ke Admin untuk mereset password Anda.</p>
                
                {{-- Tampilkan pesan sukses --}}
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Form Request Reset Password --}}
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    
                    {{-- Input Email SAJA --}}
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input id="loginEmail" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="" required autofocus />
                            <label for="loginEmail">Email</label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('login') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send"></i> Kirim
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-1 text-center">
                    <small class="text-muted">Admin akan mereset password Anda dan mengirimkan password baru melalui email.</small>
                </p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js" crossorigin="anonymous"></script>
</body>
</html>