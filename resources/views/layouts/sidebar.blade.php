@auth
    @if(auth()->user()->role === 'admin')
        
        <div x-data="{ sidebarOpen: false, isCollapsed: false }">

            <button class="fixed top-4 left-4 z-[1060] md:hidden p-2 bg-gray-800 text-white rounded-lg"
                    @click="sidebarOpen = !sidebarOpen">
                <i class="bi" :class="sidebarOpen ? 'bi-x-lg' : 'bi-list'"></i>
            </button>

            <div class="fixed inset-0 bg-black/50 z-[1040] md:hidden" x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"></div>

            <aside class="fixed top-0 left-0 h-screen z-[1050] bg-[#F4F0E6] dark:bg-[#141419] border-r border-[#EAE4D8] dark:border-white/10 transition-all duration-300 ease-in-out"
                   :class="{ 
                       'w-[260px] translate-x-0': sidebarOpen || !isCollapsed, 
                       'w-[80px] -translate-x-full md:translate-x-0': !sidebarOpen && isCollapsed 
                   }">

                <div @click="isCollapsed = !isCollapsed" 
                     class="flex items-center gap-3 p-4 h-[70px] cursor-pointer hover:bg-[#EAE4D8] dark:hover:bg-white/5 transition-colors">
                    
                    <img src="{{ asset('images/isotipo.webp') }}" alt="Logo" class="w-8 h-8 object-contain shrink-0">
                    
                    <h5 class="text-gray-900 dark:text-white font-bold text-lg whitespace-nowrap overflow-hidden transition-opacity duration-300" 
                        x-show="!isCollapsed || sidebarOpen">
                        OLLIN<span class="text-blue-600">CHECK</span>
                    </h5>
                </div>

                <nav class="flex flex-col mt-4 gap-2 px-2">
                    <x-nav-link route="admin.dashboard" icon="bi-speedometer2" label="Dashboard" collapsed="isCollapsed" open="sidebarOpen" />
                    <x-nav-link route="home" icon="bi-people" label="Administración" collapsed="isCollapsed" open="sidebarOpen" />
                    <x-nav-link route="admin.historial" icon="bi-clock-history" label="Historial" collapsed="isCollapsed" open="sidebarOpen" />
                </nav>

                <div class="absolute bottom-5 w-full px-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex items-center w-full p-3 rounded-xl transition-all duration-300 text-gray-500 hover:bg-red-500/10 hover:text-red-500 dark:hover:text-red-400">
                            <i class="bi bi-box-arrow-left text-xl shrink-0"></i>
                            <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300" 
                                  x-show="!isCollapsed || sidebarOpen">
                                Salir
                            </span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    @endif
@endauth