<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - FT-ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f0f3fb; 
            overflow-x: hidden; 
        }

        #wrapper { 
            display: flex; 
            width: 100%; 
        }

        /* PAGE CONTENT WRAPPER */
        #page-content-wrapper { 
            width: 100%; 
            min-height: 100vh; 
            margin-left: 260px; /* Sesuai lebar sidebar */
            transition: all 0.3s; 
        }
        
        .main-content { padding: 40px; }
        
        .card { border: none; border-radius: 15px; transition: all 0.3s; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        /* Responsive Fix */
        @media (max-width: 768px) {
            #page-content-wrapper { margin-left: 0; }
            #sidebar-wrapper { left: -260px; }
            #wrapper.toggled #sidebar-wrapper { left: 0; }
            #wrapper.toggled #page-content-wrapper { margin-left: 260px; }
            .main-content { padding: 20px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="wrapper">
        @include('Admin.Partials.sidebar')

        <div id="page-content-wrapper">
            @include('Admin.Partials.navbar')

            <div class="main-content">
                @yield('content')
            </div>
            
            <footer class="text-center py-4 text-muted small mt-auto">
                © {{ date('Y') }} Feedback Tracker - Polinema Kediri
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const wrapper = document.getElementById('wrapper');
        if(menuToggle) {
            menuToggle.addEventListener('click', e => {
                e.preventDefault();
                wrapper.classList.toggle('toggled');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>