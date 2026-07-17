@extends('layouts.app')

@section('content')

<div class="min-h-screen flex justify-center items-center relative overflow-hidden p-5 bg-gradient-to-br from-gray-100 via-gray-50 to-gray-200 dark:from-[#06101b] dark:via-[#0d1626] dark:to-[#09131e] transition-colors duration-300">

   <!-- Fondo para TEMA CLARO (se muestra por defecto, se oculta en oscuro) -->
    <div class="absolute inset-0 bg-center bg-no-repeat bg-[length:900px] opacity-[0.04] blur-[5px] scale-[1.15] block dark:hidden" 
         style="background-image:url('{{ asset('images/ollintem-logo-blanc.png') }}')"></div>

    <!-- Fondo para TEMA OSCURO (se oculta en claro, se muestra en oscuro) -->
    <div class="absolute inset-0 bg-center bg-no-repeat bg-[length:900px] opacity-[0.08] blur-[5px] scale-[1.15] hidden dark:block" 
         style="background-image:url('{{ asset('images/ollintem-logo.png') }}')"></div>

    <!-- Capa de superposición: clara en día, oscura en noche -->
   <div class="absolute inset-0 bg-white/20 dark:bg-[#050a14]/40 transition-colors duration-300"></div>

    <!-- Tarjeta principal -->
    <div class="relative z-10 w-full max-w-[390px] bg-white/80 dark:bg-[#121926]/[.82] border border-gray-200 dark:border-white/[.08] backdrop-blur-2xl rounded-2xl shadow-[0_15px_45px_rgba(0,0,0,.1)] dark:shadow-[0_15px_45px_rgba(0,0,0,.50)] overflow-hidden transition-all duration-300">

        <div class="text-center px-5 pt-[18px] pb-1.5">
    
    <!-- Logo para el TEMA CLARO (se muestra en modo claro, se oculta en oscuro) -->
    <img src="{{ asset('images/ollintem-logo-blanc.png') }}" 
         class="w-[170px] mx-auto mb-3 block dark:hidden">

    <!-- Logo para el TEMA OSCURO (se oculta en modo claro, se muestra en oscuro) -->
    <img src="{{ asset('images/ollintem-logo.png') }}" 
         class="w-[170px] mx-auto mb-3 hidden dark:block">

    <h2 class="text-gray-800 dark:text-white text-[2rem] font-bold mb-0.5 leading-[1.15]">Bienvenido de nuevo</h2>
    <p class="text-gray-500 dark:text-gray-400 mb-0">Inicia sesión en tu espacio de trabajo</p>
</div>

        <div class="p-5">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="text-gray-700 dark:text-white font-medium dark:font-normal block mb-1.5">Correo electrónico</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full bg-gray-50 dark:bg-[#0f1724] border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-white/[.08]' }} text-gray-900 dark:text-white p-3.5 rounded-xl box-border focus:bg-white dark:focus:bg-[#101b2c] focus:border-[#1f8fff] focus:shadow-[0_0_0_.20rem_rgba(0,132,255,.25)] focus:outline-none transition-colors"
                        required
                        autofocus>

                    @error('email')
                        <span class="block text-red-500 dark:text-red-400 text-sm mt-1.5">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="text-gray-700 dark:text-white font-medium dark:font-normal block mb-1.5">Contraseña</label>

                    <input
                        type="password"
                        name="password"
                        class="w-full bg-gray-50 dark:bg-[#0f1724] border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-white/[.08]' }} text-gray-900 dark:text-white p-3.5 rounded-xl box-border focus:bg-white dark:focus:bg-[#101b2c] focus:border-[#1f8fff] focus:shadow-[0_0_0_.20rem_rgba(0,132,255,.25)] focus:outline-none transition-colors"
                        required>

                    @error('password')
                        <span class="block text-red-500 dark:text-red-400 text-sm mt-1.5">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-[#0f1724] text-[#0d6efd] focus:ring-[#0d6efd]">

                    <label class="text-gray-600 dark:text-gray-300 select-none cursor-pointer" for="remember">
                        Recordar mi sesión
                    </label>
                </div>

                <!-- El botón se mantiene igual porque sus colores combinan perfecto en ambos temas -->
                <button class="w-full p-3 border-0 rounded-xl bg-gradient-to-r from-[#0d6efd] to-[#0b84ff] text-white font-semibold transition-all duration-300 cursor-pointer hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(13,110,253,.35)]">
                    Acceder al sistema
                </button>

                @if(Route::has('password.request'))
                    <div class="text-center mt-4">
                        <a class="text-gray-500 dark:text-gray-400 no-underline hover:text-gray-900 dark:hover:text-white transition-colors" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                @endif

            </form>

        </div>

    </div>

</div>

@endsectiongit 