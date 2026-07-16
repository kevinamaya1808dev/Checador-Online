<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Checador') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="bg-[#1a1d21] text-gray-300 [font-family:'Segoe_UI',sans-serif]">

    <div id="app" class="flex min-h-screen">

        @auth
            @if(auth()->user()->role === 'admin')
                @include('layouts.sidebar')
            @endif
        @endauth

        <main
            class="flex-grow bg-[#1a1d21] min-w-0 {{ auth()->check() && auth()->user()->role === 'admin' ? 'p-[1.5rem] md:ml-[260px] pt-[4.5rem] md:pt-[1.5rem]' : 'p-0' }}"
        >
            @yield('content')
        </main>
    </div>
</body>
</html>

