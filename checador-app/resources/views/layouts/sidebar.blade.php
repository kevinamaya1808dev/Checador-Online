<style>
    .modern-sidebar {
        background: rgba(20, 20, 25, 0.95);
        backdrop-filter: blur(10px);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        width: 260px;
        transition: all 0.3s ease;
    }
    .nav-link {
        color: #888 !important;
        padding: 15px 25px !important;
        transition: 0.3s;
        border-left: 3px solid transparent;
    }
    .nav-link:hover, .nav-link.active {
        color: #fff !important;
        background: rgba(255, 255, 255, 0.05);
        border-left: 3px solid #0d6efd; /* Azul corporativo */
    }
</style>

@auth
    @if(auth()->user()->role === 'admin')
        <div class="modern-sidebar vh-100 position-fixed">
            <div class="p-4">
                <h5 class="text-white fw-bold">ORION<span class="text-primary">.OS</span></h5>
            </div>
            <nav class="nav flex-column mt-2">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Administración
                </a>
            </nav>
            <div class="position-absolute bottom-0 w-100 p-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-box-arrow-left"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    @endif
@endauth