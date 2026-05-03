<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Feedback Tracker - Polinema Kediri</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { 
            font-family: 'Instrument Sans', sans-serif; 
            background-color: #F8FAFC; 
            color: #1b1b18;
        }
        .hero-section {
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
        }
        .main-card {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            border: none;
        }
        .btn-mahasiswa {
            background-color: #0ea5e9; 
            color: white;
            border: none;
            transition: all 0.3s;
        }
        .btn-mahasiswa:hover {
            background-color: #0284c7;
            color: white;
            transform: translateY(-2px);
        }
        .visual-side {
            background: linear-gradient(135deg, #4B49AC 0%, #0ea5e9 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .glass-icon {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            padding: 30px;
            font-size: 3rem;
        }
        .text-custom-blue {
            color: #4B49AC;
        }
    </style>
</head>
<body>

    <header class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="fw-bold fs-4">Feedback<span class="text-custom-blue">Tracker</span></div>
            
            <nav class="d-flex gap-2 align-items-center">
                @auth
                    {{-- Menggunakan rute pengalih yang baru kita buat di web.php --}}
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm px-4 shadow-sm fw-bold">Dashboard</a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-none fw-medium">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login.mahasiswa') }}" class="btn btn-mahasiswa btn-sm px-4 shadow-sm fw-bold">
                        Login Mahasiswa
                    </a>

                    <a href="{{ route('login.dosen') }}" class="btn btn-outline-info btn-sm px-4">
                        Login Dosen
                    </a>

                    <a href="{{ route('login.admin') }}" class="btn btn-link btn-sm text-muted text-decoration-none fw-medium">
                        Admin
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container hero-section">
        <div class="card main-card w-100 shadow-lg border-0">
            <div class="row g-0">
                <div class="col-md-7 p-5 bg-white">
                    <h1 class="display-5 fw-bold mb-3">Evaluasi Perkuliahan Jadi Lebih Mudah</h1>
                    <p class="text-muted fs-5 mb-4">
                        Sampaikan tingkat pemahaman Anda secara jujur dan bantu dosen meningkatkan kualitas pengajaran di Politeknik Negeri Malang PSDKU Kediri.
                    </p>

                    <div class="row g-4 mt-2">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary" style="font-size: 1.5rem;">🛡️</div>
                                <div>
                                    <div class="fw-bold">Privasi Terjaga</div>
                                    <div class="small text-muted">Feedback Anda anonim dan aman.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary" style="font-size: 1.5rem;">📊</div>
                                <div>
                                    <div class="fw-bold">Hasil Real-time</div>
                                    <div class="small text-muted">Statistik langsung tersedia untuk dosen.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 visual-side p-5 d-none d-md-flex">
                    <div class="text-center">
                        <div class="glass-icon mb-4 shadow-sm">🎓</div>
                        <h2 class="fw-bold text-white">Polinema Kediri</h2>
                        <p class="text-white opacity-75">Sistem Pelacak Feedback Mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-5 text-muted small">
        © {{ date('Y') }} Polinema PSDKU Kediri - Dibuat untuk Pengembangan Akademik
    </footer>

</body>
</html>