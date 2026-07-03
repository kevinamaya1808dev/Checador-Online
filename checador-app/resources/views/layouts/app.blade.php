<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Checador') }}</title>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { background-color: #1a1d21; color: #d1d5db; font-family: 'Segoe UI', sans-serif; }
        
        /* Estilos base para contenedores */
        .main-wrapper { display: flex; min-height: 100vh; }
        .content-area { flex-grow: 1; padding: 2rem; background-color: #1a1d21; }

        /* Estilos de elementos Dark Corporate */
        .card { background-color: #2c3035; border: 1px solid #3e444a; color: #e5e7eb; }
        .card-header { background-color: #212529 !important; border-bottom: 1px solid #3e444a !important; color: #ffffff; }
        .form-control { background-color: #1a1d21; border: 1px solid #4b5563; color: #ffffff; }
        .form-control:focus { border-color: #3b82f6; box-shadow: none; }

        /* En tu app.blade.php o en un archivo CSS */
@media (min-width: 768px) {
    main {
        margin-left: 260px; /* Igual al width del sidebar */
    }
}
    </style>
</head>
<body>
    <div id="app" class="main-wrapper" style="display: flex;">
    @auth
        @include('layouts.sidebar')
    @endauth

    <main class="content-area" style="flex: 1; min-width: 0; padding: 2rem;">
        @yield('content')
    </main>
</div>
</body>
<script>
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    }

    // Cargar preferencia guardada
    const savedTheme = localStorage.getItem('theme') || 'dark'; // 'dark' por defecto
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
</script>
</html>