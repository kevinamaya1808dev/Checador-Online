@auth
    @if(auth()->user()->role === 'admin')

        <!-- Botón hamburguesa: solo visible en móvil -->
        <button
            class="fixed top-0 left-0 m-3 z-[1060] md:hidden w-9 h-9 rounded-lg flex items-center justify-center bg-[#2c3035] border border-[#3e444a] text-white transition-colors duration-200 hover:bg-[#3e444a] hover:border-[#4b5563]"
            @click="sidebarOpen = !sidebarOpen"
            type="button"
            :aria-expanded="sidebarOpen"
            aria-label="Abrir menú"
        >
            <i
                class="bi text-xl transition-transform duration-[250ms]"
                :class="sidebarOpen ? 'bi-x-lg rotate-90' : 'bi-list rotate-0'"
            ></i>
        </button>

        <!-- Fondo oscuro al abrir en móvil: toca fuera para cerrar -->
        <div
            class="fixed inset-0 bg-black/50 z-[1040] md:hidden"
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
        ></div>

        <div
            class="fixed top-0 left-0 h-screen w-[260px] bg-[rgba(20,20,25,0.95)] backdrop-blur-md border-r border-white/5 z-[1050] transition-transform duration-300 -translate-x-full md:!translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }"
        >

            <div class="p-[1.5rem] pt-[4.5rem] md:pt-[1.5rem]">
                <h5 class="text-white font-bold text-lg">OLLIN<span class="text-blue-600">CHECK</span></h5>
            </div>

            <nav class="flex flex-col mt-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-[25px] py-[15px] transition-colors duration-300 border-l-[3px] {{ request()->routeIs('admin.dashboard') ? 'text-white bg-white/5 border-blue-600' : 'text-gray-500 border-transparent hover:text-white hover:bg-white/5 hover:border-blue-600' }}">
                    <i class="bi bi-speedometer2 mr-2"></i> Dashboard
                </a>

                <a href="{{ route('home') }}"
                   class="flex items-center px-[25px] py-[15px] transition-colors duration-300 border-l-[3px] {{ request()->routeIs('home') ? 'text-white bg-white/5 border-blue-600' : 'text-gray-500 border-transparent hover:text-white hover:bg-white/5 hover:border-blue-600' }}">
                    <i class="bi bi-people mr-2"></i> Administración
                </a>

                <a href="{{ route('admin.historial') }}"
                   class="flex items-center px-[25px] py-[15px] transition-colors duration-300 border-l-[3px] {{ request()->routeIs('admin.historial') ? 'text-white bg-white/5 border-blue-600' : 'text-gray-500 border-transparent hover:text-white hover:bg-white/5 hover:border-blue-600' }}">
                    <i class="bi bi-clock-history mr-2"></i>
                    Historial de Jornadas
                </a>
            </nav>

            <div class="absolute bottom-0 w-full p-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 border border-gray-500 text-gray-300 hover:bg-white/5 rounded-lg px-3 py-1.5 text-sm transition-colors">
                        <i class="bi bi-box-arrow-left"></i> Salir
                    </button>
                </form>
            </div>
        </div>

    @endif
@endauth