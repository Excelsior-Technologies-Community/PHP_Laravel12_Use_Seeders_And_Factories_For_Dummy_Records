<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel 12 Blog')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .card {
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .post-meta {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .footer {
            background-color: #f8f9fa;
            margin-top: 50px;
        }

        .dark-mode {
            background: #121212;
            color: white;
        }


        .dark-mode .navbar {
            background: #1e1e1e !important;
        }


        .dark-mode .navbar-brand,
        .dark-mode .nav-link {
            color: white !important;
        }


        .dark-mode .card {
            background: #1f1f1f;
            color: white;
        }


        .dark-mode .card:hover {
            transform: translateY(-5px);
        }


        .dark-mode .footer {
            background: #1e1e1e;
            color: white;
        }


        .dark-mode .text-muted {
            color: #aaa !important;
        }


        .dark-mode .post-meta {
            color: #bbb;
        }


        .dark-mode .list-group-item {
            background: #1f1f1f;
            color: white;
        }


        .dark-mode .form-control {
            background: #2b2b2b;
            color: white;
            border-color: #555;
        }
    </style>

    @stack('styles')
    
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">Laravel Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">

                    <button id="darkModeBtn" class="btn btn-dark">
                        🌙 Dark Mode
                    </button>

                    <a href="#" class="btn btn-outline-primary">
                        Login
                    </a>

                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Laravel 12 Blog</h5>
                    <p>A sample blog application built with Laravel 12 featuring database seeders and factories.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Laravel Blog. All rights reserved.</p>
                    <p class="text-muted">
                        Total Posts: {{ \App\Models\Post::count() }} |
                        Total Users: {{ \App\Models\User::count() }}
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        let darkBtn = document.getElementById('darkModeBtn');


        if (localStorage.getItem('theme') === 'dark') {

            document.body.classList.add('dark-mode');

            if (darkBtn) {
                darkBtn.innerHTML = "☀ Light Mode";
            }

        }


        if (darkBtn) {

            darkBtn.addEventListener('click', function() {


                document.body.classList.toggle('dark-mode');


                if (document.body.classList.contains('dark-mode')) {


                    localStorage.setItem('theme', 'dark');

                    darkBtn.innerHTML = "☀ Light Mode";


                } else {


                    localStorage.setItem('theme', 'light');

                    darkBtn.innerHTML = "🌙 Dark Mode";


                }


            });

        }
    </script>


    @stack('scripts')
</body>

</html>