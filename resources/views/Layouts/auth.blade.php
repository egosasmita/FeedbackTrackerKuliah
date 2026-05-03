<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Feedback Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url("{{ asset('img/background_login.png') }}");
            background-size: cover; 
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh; 
            display: flex; 
            align-items: center;
            padding: 20px 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95); 
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); 
            padding: 30px;
        }
        .brand-section { 
            color: #003366; 
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
        }
        .logo-img {
            width: 250px;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            body {
                display: block;
            }
            .brand-section {
                margin-bottom: 30px;
            }
            .logo-img {
                width: 150px;
            }
            .brand-section h1 {
                font-size: 1.5rem;
            }
            .brand-section h4 {
                font-size: 1.1rem;
            }
        }
    </style>

    @yield('additional_style')
</head>
<body>

<div class="container">
    <div class="row align-items-center">
        <div class="col-lg-7 col-md-6 text-center text-md-start brand-section">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start mb-4">
                <img src="{{ asset('img/logo_polinema.png') }}" alt="Logo" class="logo-img mb-3 mb-md-0">
                <div class="ms-md-4 text-center text-md-start">
                    <h1 class="fw-bold border-bottom border-dark pb-2">Feedback Tracker</h1>
                    <h4 class="mt-2">Polinema PSDKU Kediri</h4>
                    <p class="mb-0">Evaluasi Perkuliahan & Kepuasan Mahasiswa</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 offset-lg-1">
            <div class="login-card">
                @yield('content')
            </div>
            
            <div class="text-center mt-3 small text-white bg-dark py-2 rounded shadow-sm">
                © 2026 - Sistem Informasi Akademik - Polinema
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        var x = document.getElementById("password");
        if (x) {
            x.type = x.type === "password" ? "text" : "password";
        }
    }
</script>
</body>
</html>