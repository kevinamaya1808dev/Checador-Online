@extends('layouts.app')

@section('content')

<div class="min-h-screen flex justify-center items-center relative overflow-hidden p-5 bg-gradient-to-br from-[#06101b] via-[#0d1626] to-[#09131e]">

    <div class="absolute inset-0 bg-center bg-no-repeat bg-[length:900px] opacity-[0.08] blur-[5px] scale-[1.15]" style="background-image:url('{{ asset('images/ollintem-logo.png') }}')"></div>

    <div class="absolute inset-0 bg-[#050a14]/55"></div>

    <div class="relative z-10 w-full max-w-[390px] bg-[#121926]/[.82] border border-white/[.08] backdrop-blur-2xl rounded-2xl shadow-[0_15px_45px_rgba(0,0,0,.50)] overflow-hidden">

        <div class="text-center px-5 pt-[18px] pb-1.5">
            <img src="{{ asset('images/ollintem-logo.png') }}" class="w-[170px] mx-auto mb-3">
            <h2 class="text-white text-[2rem] font-bold mb-0.5 leading-[1.15]">Bienvenido de nuevo</h2>
            <p class="text-gray-400 mb-0">Inicia sesión en tu espacio de trabajo</p>
        </div>

        <div class="p-5">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="text-white block mb-1.5">Correo electrónico</label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full bg-[#0f1724] border {{ $errors->has('email') ? 'border-red-500' : 'border-white/[.08]' }} text-white p-3.5 rounded-xl box-border focus:bg-[#101b2c] focus:text-white focus:border-[#1f8fff] focus:shadow-[0_0_0_.20rem_rgba(0,132,255,.25)] focus:outline-none"
                        required
                        autofocus>

                    @error('email')
                        <span class="block text-red-400 text-sm mt-1.5">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="text-white block mb-1.5">Contraseña</label>

                    <input
                        type="password"
                        name="password"
                        class="w-full bg-[#0f1724] border {{ $errors->has('password') ? 'border-red-500' : 'border-white/[.08]' }} text-white p-3.5 rounded-xl box-border focus:bg-[#101b2c] focus:text-white focus:border-[#1f8fff] focus:shadow-[0_0_0_.20rem_rgba(0,132,255,.25)] focus:outline-none"
                        required>

                    @error('password')
                        <span class="block text-red-400 text-sm mt-1.5">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember">

                    <label class="text-gray-300" for="remember">
                        Recordar mi sesión
                    </label>
                </div>

                <button class="w-full p-3 border-0 rounded-xl bg-gradient-to-r from-[#0d6efd] to-[#0b84ff] text-white font-semibold transition-all duration-300 cursor-pointer hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(13,110,253,.35)]">
                    Acceder al sistema
                </button>

                @if(Route::has('password.request'))
                    <div class="text-center mt-4">
                        <a class="text-gray-400 no-underline hover:text-white" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                @endif

            </form>

        </div>

    </div>

</div>

@endsection