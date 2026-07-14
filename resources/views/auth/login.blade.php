@extends('layouts.app')

@section('content')

<style>
    body{
        margin:0;
        overflow:hidden;
        background:#07111d;
    }

    .login-page{
        min-height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        position:relative;
        overflow:hidden;
        padding:20px;
        background:linear-gradient(135deg,#06101b,#0d1626,#09131e);
       
    }

    /* Logo de fondo */

    .login-page::before{
        content:"";
        position:absolute;
        inset:0;

        background:url('{{ asset('images/ollintem-logo.png') }}') center center no-repeat;
        background-size:900px;

        opacity:.08;

        filter:blur(5px);

        transform:scale(1.15);

        animation:zoomBackground 20s infinite alternate;
    }

    /* Oscurecer fondo */

    .login-page::after{
        content:"";
        position:absolute;
        inset:0;
        background:rgba(5,10,20,.55);
    }

    @keyframes zoomBackground{

        from{
            transform:scale(1.05);
        }

        to{
            transform:scale(1.15);
        }

    }

    .login-card{
    position:relative;
    z-index:2;
    width:100%;
    max-width:390px;

        background:rgba(18,25,38,.82);

        border:1px solid rgba(255,255,255,.08);

        backdrop-filter:blur(24px);

        border-radius:18px;

        box-shadow:
            0 15px 45px rgba(0,0,0,.50);

        overflow:hidden;

        animation:fadeUp .8s ease;
    }

    @keyframes fadeUp{

        from{
            opacity:0;
            transform:translateY(30px);
        }

        to{
            opacity:1;
            transform:translateY(0);
        }

    }

    .login-header{
    text-align:center;
    padding:18px 20px 6px;
}
    .login-header img{
    width:170px;
    margin-bottom:12px;
}

    .login-header h2{
         color:#fff;
    font-size:2rem;
    font-weight:700;
    margin-bottom:2px;
    line-height:1.15;
    }

    .login-header p{
        color:#9ca3af;
        margin-bottom:0;
    }

    .login-body{
    padding:20px;
}

    .form-label{
        color:white;
    }

    .form-control{

        background:#0f1724;

        border:1px solid rgba(255,255,255,.08);

        color:white;

        padding:14px;

        border-radius:12px;
    }

    .form-control:focus{

        background:#101b2c;

        color:white;

        border-color:#1f8fff;

        box-shadow:0 0 0 .20rem rgba(0,132,255,.25);

    }

    .btn-login{

        width:100%;

        padding:12px;

        border:none;

        border-radius:12px;

        background:linear-gradient(90deg,#0d6efd,#0b84ff);

        color:white;

        font-weight:600;

        transition:.3s;
    }

    .btn-login:hover{

        transform:translateY(-2px);

        box-shadow:0 12px 25px rgba(13,110,253,.35);

    }

    .forgot{

        color:#98a2b3;

        text-decoration:none;
    }

    .forgot:hover{
        color:white;
    }

    .form-check-label{
        color:#c7c7c7;
    }

</style>

<div class="login-page">

    <div class="login-card">

        <div class="login-header">

            <img src="{{ asset('images/ollintem-logo.png') }}">

            <h2>Bienvenido de nuevo</h2>

            <p>Inicia sesión en tu espacio de trabajo</p>

        </div>

        <div class="login-body">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                        autofocus>

                    @error('email')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required>

                    @error('password')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>

                <div class="form-check mb-3">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember">

                    <label class="form-check-label" for="remember">

                        Recordar mi sesión

                    </label>

                </div>

                <button class="btn-login">

                    Acceder al sistema

                </button>

                @if(Route::has('password.request'))

                    <div class="text-center mt-4">

                        <a class="forgot" href="{{ route('password.request') }}">

                            ¿Olvidaste tu contraseña?

                        </a>

                    </div>

                @endif

            </form>

        </div>

    </div>

</div>

@endsection