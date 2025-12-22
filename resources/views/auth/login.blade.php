<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login | Sistem Inventory RS</title>
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
                <p class="login-box-msg">Silakan login untuk memulai sesi</p>

                {{-- Form Login Laravel --}}
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    {{-- Input Email --}}
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input id="loginEmail" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="" required autofocus />
                            <label for="loginEmail">Email</label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input id="loginPassword" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="" required />
                            <label for="loginPassword">Password</label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault"> Ingat Saya </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Masuk</button>
                            </div>
                        </div>
                        </div>
                </form>

                {{-- Link Forgot Password (Opsional) --}}
                {{-- Kalau mau dihapus, hapus paragraph di bawah ini --}}
                <p class="mb-1 mt-3">
                    <a href="{{ route('password.request') }}">Lupa password saya</a>
                </p>
                
                {{-- Link Register DIHAPUS (Sesuai request) --}}
            </div>
            </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js" crossorigin="anonymous"></script>
</body>
</html>