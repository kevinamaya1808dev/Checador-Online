<div class="entrada bg-slate-900/85 backdrop-blur-[15px] border border-white/[0.08] rounded-3xl p-5 sm:p-6 h-full flex flex-col shadow-lg" style="animation-delay:.16s">

    <div class="flex items-center justify-between mb-4">
        <p class="uppercase text-slate-500 font-bold tracking-[2px] text-[0.7rem] mb-0">
            Acciones
        </p>

        <span class="h-px flex-1 ml-3 bg-gradient-to-r from-white/10 to-transparent"></span>
    </div>

    <div class="flex flex-col gap-3.5">

        {{-- ========================================= --}}
        {{-- REGISTRAR ENTRADA --}}
        {{-- ========================================= --}}
        <form action="{{ route('becario.checar') }}" method="POST" class="m-0">
            @csrf

            <button
                type="submit"
                class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                       bg-slate-900/60 border-blue-500/40
                       transition-all duration-300 ease-out
                       hover:-translate-y-1 hover:border-blue-400/70 hover:shadow-[0_18px_35px_-15px_rgba(37,99,235,0.55)]
                       active:translate-y-0 active:scale-[0.98]
                       disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                       {{ $destacarEntrada ? 'ring-1 ring-blue-400/40 shadow-[0_0_30px_-8px_rgba(59,130,246,0.5)]' : '' }}"
                @disabled(!$puedeEntrada)
            >

                <span class="absolute left-0 top-0 h-full w-1 bg-blue-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>

                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-blue-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                @if($destacarEntrada)
                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-blue-300 bg-blue-500/10 border border-blue-400/30 rounded-full px-2 py-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                        Disponible
                    </span>
                @endif

                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                             bg-gradient-to-br from-blue-500 to-blue-700 shadow-[0_8px_20px_-4px_rgba(37,99,235,0.65)]
                             transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">

                    <i class="bi bi-box-arrow-in-right"></i>

                </span>

                <span class="relative flex-1 min-w-0">

                    <span class="block font-bold text-base sm:text-lg text-blue-300 tracking-tight">
                        Registrar entrada
                    </span>

                    <span class="block text-sm text-slate-400 mt-0.5">
                        Inicia el registro de tu turno
                    </span>

                </span>

                <i class="bi bi-chevron-right relative text-white/0 group-hover:text-white/50 transition-all duration-300 group-hover:translate-x-1"></i>

            </button>

        </form>



        {{-- ========================================= --}}
        {{-- GESTIONAR PAUSA --}}
        {{-- ========================================= --}}
        <div>

            <button
                type="button"
                class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                       bg-slate-900/60 border-amber-500/40
                       transition-all duration-300 ease-out
                       hover:-translate-y-1 hover:border-amber-400/70 hover:shadow-[0_18px_35px_-15px_rgba(217,119,6,0.55)]
                       active:translate-y-0 active:scale-[0.98]
                       disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                       {{ $destacarPausar ? 'ring-1 ring-amber-400/40 shadow-[0_0_30px_-8px_rgba(245,158,11,0.5)]' : '' }}"
                onclick="togglePausaMenu()"
                @disabled(!$puedePausar)
            >

                <span class="absolute left-0 top-0 h-full w-1 bg-amber-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>

                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-amber-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                @if($destacarPausar)
                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-300 bg-amber-500/10 border border-amber-400/30 rounded-full px-2 py-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Disponible
                    </span>
                @endif

                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                             bg-gradient-to-br from-amber-500 to-amber-700 shadow-[0_8px_20px_-4px_rgba(217,119,6,0.65)]
                             transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">

                    <i class="bi bi-cup-hot"></i>

                </span>

                <span class="relative flex-1 min-w-0">

                    <span class="block font-bold text-base sm:text-lg text-amber-300 tracking-tight">
                        Gestionar pausa
                    </span>

                    <span class="block text-sm text-slate-400 mt-0.5">
                        Justificación obligatoria
                    </span>

                </span>

                <i id="pausaChevron"
                   class="bi bi-chevron-down relative text-white/40 transition-transform duration-300">
                </i>

            </button>



            {{-- Menú desplegable --}}
            <div
                id="pausaMenu"
                class="max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-out">

                <form
                    action="{{ route('becario.iniciarPausa') }}"
                    method="POST"
                    class="bg-slate-800/50 border border-amber-500/30 rounded-2xl p-3 mt-2">

                    @csrf

                    <label class="block text-sm text-amber-400 font-bold uppercase mb-2 text-[0.7rem]">
                        Motivo de pausa
                    </label>

                    <select
                        name="motivo"
                        class="w-full bg-[#0f1724] border border-white/10 text-white text-sm rounded-lg px-3 py-2 mb-3 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/25 focus:outline-none">

                        <option value="Almuerzo">Almuerzo</option>

                        <option value="Personal">Personal</option>

                    </select>

                    <button
                        type="submit"
                        class="w-full bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold uppercase text-sm rounded-lg px-4 py-2.5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        @disabled(!$puedePausar)
                    >

                        Iniciar ahora

                    </button>

                </form>

            </div>

        </div>

                {{-- ========================================= --}}
        {{-- FINALIZAR PAUSA --}}
        {{-- ========================================= --}}
        <form id="formFinalizarPausa"
              action="{{ route('becario.finalizarPausa') }}"
              method="POST"
              class="m-0">

            @csrf

            <button
                type="button"
                class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                       bg-slate-900/60 border-blue-500/40
                       transition-all duration-300 ease-out
                       hover:-translate-y-1 hover:border-blue-400/70 hover:shadow-[0_18px_35px_-15px_rgba(37,99,235,0.55)]
                       active:translate-y-0 active:scale-[0.98]
                       disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                       {{ $destacarReanudar ? 'ring-1 ring-blue-400/40 shadow-[0_0_30px_-8px_rgba(59,130,246,0.5)]' : '' }}"
                onclick="openModal('modalFinalizarPausa')"
                @disabled(!$puedeReanudar)
            >

                <span class="absolute left-0 top-0 h-full w-1 bg-blue-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>

                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-blue-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                @if($destacarReanudar)
                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-blue-300 bg-blue-500/10 border border-blue-400/30 rounded-full px-2 py-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                        Disponible
                    </span>
                @endif

                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                             bg-gradient-to-br from-blue-500 to-blue-700 shadow-[0_8px_20px_-4px_rgba(37,99,235,0.65)]
                             transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">

                    <i class="bi bi-play-fill"></i>

                </span>

                <span class="relative flex-1 min-w-0">

                    <span class="block font-bold text-base sm:text-lg text-blue-300 tracking-tight">
                        Finalizar pausa
                    </span>

                    <span class="block text-sm text-slate-400 mt-0.5">
                        Reanuda tus actividades
                    </span>

                </span>

                <i class="bi bi-chevron-right relative text-white/0 group-hover:text-white/50 transition-all duration-300 group-hover:translate-x-1"></i>

            </button>

        </form>



        {{-- ========================================= --}}
        {{-- REGISTRAR SALIDA --}}
        {{-- ========================================= --}}
        <form id="formSalida"
              action="{{ route('becario.salida') }}"
              method="POST"
              class="m-0">

            @csrf

            <button
                type="button"
                class="group relative isolate w-full text-left overflow-hidden rounded-2xl border p-4 sm:p-5 flex items-center gap-4
                       bg-slate-900/60 border-red-500/40
                       transition-all duration-300 ease-out
                       hover:-translate-y-1 hover:border-red-400/70 hover:shadow-[0_18px_35px_-15px_rgba(220,38,38,0.55)]
                       active:translate-y-0 active:scale-[0.98]
                       disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:hover:translate-y-0 disabled:hover:shadow-none
                       {{ $destacarSalir ? 'ring-1 ring-red-400/40 shadow-[0_0_30px_-8px_rgba(239,68,68,0.5)]' : '' }}"
                onclick="openModal('modalSalida')"
                @disabled(!$puedeSalir)
            >

                <span class="absolute left-0 top-0 h-full w-1 bg-red-500 origin-center scale-y-0 group-hover:scale-y-100 transition-transform duration-300 rounded-r"></span>

                <span class="pointer-events-none absolute -right-8 -top-8 w-28 h-28 rounded-full bg-red-500/10 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>

                @if($destacarSalir)
                    <span class="absolute top-3 right-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-red-300 bg-red-500/10 border border-red-400/30 rounded-full px-2 py-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse"></span>
                        Disponible
                    </span>
                @endif

                <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl text-white
                             bg-gradient-to-br from-red-500 to-red-700 shadow-[0_8px_20px_-4px_rgba(185,28,28,0.65)]
                             transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">

                    <i class="bi bi-box-arrow-left"></i>

                </span>

                <span class="relative flex-1 min-w-0">

                    <span class="block font-bold text-base sm:text-lg text-red-300 tracking-tight">
                        Registrar salida
                    </span>

                    <span class="block text-sm text-slate-400 mt-0.5">
                        Finaliza tu turno
                    </span>

                </span>

                <i class="bi bi-chevron-right relative text-white/0 group-hover:text-white/50 transition-all duration-300 group-hover:translate-x-1"></i>

            </button>

        </form>

                {{-- ========================================= --}}
        {{-- RECORDATORIO --}}
        {{-- ========================================= --}}
        <div class="bg-slate-800/50 border border-white/[0.06] rounded-2xl mt-auto p-4 flex gap-3 items-start">

            <span class="w-8 h-8 rounded-lg bg-blue-500/15 border border-blue-400/25 flex items-center justify-center shrink-0">

                <i class="bi bi-info-circle-fill text-blue-400 text-sm"></i>

            </span>

            <div>

                <p class="font-bold text-sm mb-0">
                    Recuerda registrar tus pausas
                </p>

                <p class="text-slate-400 mb-0 text-[0.75rem]">
                    Para llevar un control correcto de tu tiempo laboral.
                </p>

            </div>

        </div>

    </div>

</div>