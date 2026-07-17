{{-- NOTIFICACIONES: aparecen a un costado y se autodestruyen a los 3s (JS plano, sin Bootstrap) --}}
<div class="fixed top-0 right-0 p-3 z-[1090] flex flex-col gap-2">
    @if (session('success'))
        <div data-toast class="bg-white/95 dark:bg-slate-900/90 backdrop-blur-[15px] border border-[#EAE4D8] dark:border-white/[0.08] rounded-2xl overflow-hidden min-w-[280px] shadow-lg dark:shadow-2xl transition-colors duration-300" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="flex items-center">
                <div class="flex items-center gap-2 p-3 text-gray-800 dark:text-white">
                    <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                    {{ session('success') }}
                </div>
                <button type="button" data-toast-close class="ml-auto mr-3 text-gray-400 hover:text-gray-700 dark:text-white/70 dark:hover:text-white transition-colors" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div data-toast class="bg-white/95 dark:bg-slate-900/90 backdrop-blur-[15px] border border-[#EAE4D8] dark:border-white/[0.08] rounded-2xl overflow-hidden min-w-[280px] shadow-lg dark:shadow-2xl transition-colors duration-300" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="flex items-center">
                <div class="flex items-center gap-2 p-3 text-gray-800 dark:text-white">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl"></i>
                    {{ session('error') }}
                </div>
                <button type="button" data-toast-close class="ml-auto mr-3 text-gray-400 hover:text-gray-700 dark:text-white/70 dark:hover:text-white transition-colors" aria-label="Cerrar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endif
</div>