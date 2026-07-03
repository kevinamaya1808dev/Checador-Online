<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Checador') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* Paleta: Gris Carbón y Azul Acero */
        body { background-color: #1a1d21; color: #d1d5db; font-family: 'Segoe UI', sans-serif; }
        
        /* Navbar elegante */
        .navbar { background-color: #212529 !important; border-bottom: 1px solid #374151; }
        .navbar-brand { color: #f9fafb !important; font-weight: 700; letter-spacing: 0.5px; }
        .nav-link { color: #9ca3af !important; }
        .nav-link:hover { color: #ffffff !important; }

        /* Tarjetas (Cards) estilo oscuro */
        .card { background-color: #2c3035; border: 1px solid #3e444a; color: #e5e7eb; }
        .card-header { background-color: #212529 !important; border-bottom: 1px solid #3e444a !important; color: #ffffff; }
        
        /* Formularios */
        .form-control { background-color: #1a1d21; border: 1px solid #4b5563; color: #ffffff; }
        .form-control:focus { background-color: #1a1d21; border-color: #3b82f6; color: #ffffff; box-shadow: none; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Sistema') }}</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @else
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Salir') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>