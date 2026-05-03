<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dosen Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; overflow-x: hidden; }
        #wrapper { display: flex; width: 100%; }
        #page-content-wrapper { width: 100%; min-height: 100vh; margin-left: 260px; transition: all 0.3s; }
        .main-content { padding: 30px; }

        /* Sidebar Dosen - Gradasi Hijau/Biru Gelap */
        #sidebar-wrapper {
            position: fixed; left: 0; top: 0; height: 100vh; width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            z-index: 1050; transition: all 0.3s;
        }

        @media (max-width: 768px) {
            #page-content-wrapper { margin-left: 0; }
            #sidebar-wrapper { left: -260px; }
            #wrapper.toggled #sidebar-wrapper { left: 0; }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        @include('Dosen.Partials.sidebar')
        <div id="page-content-wrapper">
            @include('Dosen.Partials.navbar')
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    </script>
</body>
</html>