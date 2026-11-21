<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark: #0f172a;
            --brand-accent: #38bdf8;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at 12% 18%, rgba(56, 189, 248, 0.16), transparent 36%),
                radial-gradient(circle at 88% 82%, rgba(37, 99, 235, 0.18), transparent 40%),
                #f1f5f9;
            color: #0f172a;
        }

        .auth-wrapper {
            min-height: 100vh;
            padding: 2rem 0;
        }

        .auth-card {
            border: 1px solid rgba(15, 23, 42, 0.07);
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            background: #ffffff;
        }

        .auth-showcase {
            background: linear-gradient(155deg, var(--brand-dark) 0%, #1e293b 55%, #1d4ed8 100%);
            color: #ffffff;
            position: relative;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .feature-icon {
            width: 34px;
            height: 34px;
            border-radius: 0.65rem;
            background: rgba(56, 189, 248, 0.18);
            color: var(--brand-accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .form-control {
            padding: 0.68rem 0.85rem;
            border-radius: 0.65rem;
            border-color: #cbd5e1;
        }

        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 0.22rem rgba(37, 99, 235, 0.14);
        }

        .input-group .btn-toggle-pass {
            border-color: #cbd5e1;
            border-top-right-radius: 0.65rem;
            border-bottom-right-radius: 0.65rem;
            color: #475569;
        }

        .btn-login {
            padding: 0.72rem 1rem;
            border-radius: 0.65rem;
            font-weight: 600;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
        }
    </style>
</head>
<body>
<div class="container auth-wrapper d-flex align-items-center justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card auth-card">
            <div class="row g-0">
                {{-- Panel Kiri: Ringkasan Aplikasi --}}
                <div class="col-md-6 auth-showcase p-4 p-lg-5 d-flex flex-column justify-content-between">
                    <div>
                        <span class="brand-pill mb-4">
                            <i class="bi bi-check2-square"></i>
                            Task Management Workspace
                        </span>

                        <h1 class="h3 fw-bold mb-3">Atur prioritas dan tenggat tugas lebih rapi.</h1>
                        <p class="text-white-50 mb-4">
                            Kelola status pekerjaan, pantau tanggal tenggat, dan cari tugas dengan cepat dalam satu workspace terstruktur.
                        </p>

                        <div class="feature-item">
                            <span class="feature-icon"><i class="bi bi-funnel"></i></span>
                            <div>
                                <div class="fw-semibold small">Filter &amp; Pencarian Instan</div>
                                <div class="text-white-50 small">Saring task berdasarkan status atau kata kunci judul &amp; deskripsi.</div>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-icon"><i class="bi bi-calendar-check"></i></span>
                            <div>
                                <div class="fw-semibold small">Pemantauan Due Date</div>
                                <div class="text-white-50 small">Identifikasi tenggat waktu secara jelas agar tidak ada tugas terlewat.</div>
                            </div>
                        </div>

                        <div class="feature-item mb-0">
                            <span class="feature-icon"><i class="bi bi-shield-check"></i></span>
                            <div>
                                <div class="fw-semibold small">Terintegrasi Service Layer</div>
                                <div class="text-white-50 small">Didukung validasi Form Request dan transaksi database yang aman.</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-top border-white border-opacity-10 small text-white-50 d-flex justify-content-between">
                        <span>Laravel 12 &bull; Bootstrap 5</span>
                        <span>arez15</span>
                    </div>
                </div>

                {{-- Panel Kanan: Form Login --}}
                <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <h2 class="h4 fw-bold mb-1">Selamat Datang Kembali</h2>
                        <p class="text-muted small mb-0">Masukkan email dan password akun Anda untuk melanjutkan.</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 px-3 small" role="alert">
                            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal masuk</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.attempt') }}" method="POST" id="loginForm" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted" style="border-radius: 0.65rem 0 0 0.65rem; border-color: #cbd5e1;">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="admin@example.com"
                                       required
                                       autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label small fw-semibold mb-0">Password</label>
                            </div>
                            <div class="input-group">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Masukkan password"
                                       required>
                                <button type="button"
                                        class="btn btn-outline-secondary btn-toggle-pass"
                                        id="togglePassword"
                                        aria-label="Tampilkan atau sembunyikan password">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox"
                                       id="remember"
                                       name="remember"
                                       class="form-check-input"
                                       @checked(old('remember'))>
                                <label for="remember" class="form-check-label small text-muted">Ingat sesi saya</label>
                            </div>

                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" id="fillDemoBtn">
                                Isi akun demo
                            </button>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100 text-white" id="submitBtn">
                            <span id="submitText">Masuk ke Dashboard</span>
                            <span class="spinner-border spinner-border-sm ms-2 d-none" id="submitSpinner" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    const fillDemoBtn = document.getElementById('fillDemoBtn');
    const emailInput = document.getElementById('email');
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');

    toggleBtn.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
    });

    fillDemoBtn.addEventListener('click', function () {
        emailInput.value = 'admin@example.com';
        passwordInput.value = 'password123';
        emailInput.focus();
    });

    loginForm.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitText.textContent = 'Memproses...';
        submitSpinner.classList.remove('d-none');
    });
});
</script>
</body>
</html>