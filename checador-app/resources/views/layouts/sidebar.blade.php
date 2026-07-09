<style>
    .modern-sidebar {
        background: rgba(20, 20, 25, 0.95);
        backdrop-filter: blur(10px);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        width: 260px;
        transition: transform 0.3s ease;
        transform: translateX(-100%);
        z-index: 1050;
    }
    .modern-sidebar.sidebar-active {
        transform: translateX(0);
    }
    @media (min-width: 768px) {
        .modern-sidebar {
            transform: translateX(0) !important;
        }
    }
    .sidebar-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }
    .sidebar-toggle-btn {
        z-index: 1030;
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

    .sidebar-toggle-btn {
    z-index: 1030;
    background-color: #2c3035;
    border-color: #3e444a;
    width: 44px;
    height: 44px;
    border-radius: 10px;
}
</style>

@auth
    @if(auth()->user()->role === 'admin')

        <!-- Botón hamburguesa: solo visible en móvil -->
        <button
            class="btn btn-outline-light sidebar-toggle-btn d-md-none position-fixed top-0 start-0 m-3"
            @click="sidebarOpen = !sidebarOpen"
            type="button"
        >
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Fondo oscuro al abrir en móvil: toca fuera para cerrar -->
        <div
            class="sidebar-backdrop d-md-none"
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
        ></div>

        <div
            class="modern-sidebar vh-100 position-fixed transition-all duration-300"
            :class="{ 'sidebar-active': sidebarOpen }"
        >

            <div class="p-4">
                <h5 class="text-white fw-bold">OLLIN<span class="text-primary">CHECK</span></h5>
            </div>
            <nav class="nav flex-column mt-2">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Administración
                </a>

                <a href="{{ route('admin.historial') }}"
   class="nav-link {{ request()->routeIs('admin.historial') ? 'active' : '' }}">
    <i class="bi bi-clock-history me-2"></i>
    Historial
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

        <i class="bi bi-moon-fill"></i>
</button>

<script>
    const btn = document.getElementById('theme-toggle');

    if (btn) {
        btn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
        });
    }

    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
</script>
    @endif
@endauth