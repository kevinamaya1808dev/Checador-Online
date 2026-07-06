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
<body x-data="{ sidebarOpen: true }">

    <div id="app" class="main-wrapper" style="display: flex;">
        
       @auth
    @if(auth()->user()->role === 'admin')
        <div x-show="sidebarOpen" class="sidebar-wrapper">
            @include('layouts.sidebar')
        </div>
    @endif
@endauth

      <main
    class="content-area flex-grow-1 {{ auth()->check() && auth()->user()->role === 'admin' ? 'p-4' : 'p-0' }}"
    style="
        min-width:0;
        {{ auth()->check() && auth()->user()->role === 'admin' ? 'margin-left:260px;' : 'margin-left:0;' }}
    "
>
            @yield('content')
        </main>
    </div>
</body> 
</html>

