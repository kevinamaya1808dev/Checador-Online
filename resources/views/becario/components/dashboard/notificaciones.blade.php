{{-- ========================================================= --}}
{{-- NOTIFICACIONES                                             --}}
{{-- ========================================================= --}}
{{-- Se muestran en la esquina superior derecha y desaparecen  --}}
{{-- automáticamente mediante dashboard.js                     --}}
{{-- ========================================================= --}}

<div class="fixed top-0 right-0 p-3 z-[1090] flex flex-col gap-2">

    {{-- Mensaje de éxito --}}
    @if (session('success'))

        <div
            data-toast
            class="text-white bg-slate-900/90 backdrop-blur-[15px] border border-white/[0.08] rounded-2xl overflow-hidden min-w-[280px] shadow-2xl"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
        >

            <div class="flex items-center">

                <div class="flex items-center gap-2 p-3">

                    <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>

                    <span>{{ session('success') }}</span>

                </div>

                <button
                    type="button"
                    data-toast-close
                    class="ml-auto mr-3 text-white/70 hover:text-white transition-colors"
                    aria-label="Cerrar"
                >

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

        </div>

    @endif



    {{-- Mensaje de error --}}
    @if (session('error'))

        <div
            data-toast
            class="text-white bg-slate-900/90 backdrop-blur-[15px] border border-white/[0.08] rounded-2xl overflow-hidden min-w-[280px] shadow-2xl"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
        >

            <div class="flex items-center">

                <div class="flex items-center gap-2 p-3">

                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl"></i>

                    <span>{{ session('error') }}</span>

                </div>

                <button
                    type="button"
                    data-toast-close
                    class="ml-auto mr-3 text-white/70 hover:text-white transition-colors"
                    aria-label="Cerrar"
                >

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

        </div>

    @endif

</div>