{{-- =========================
     ESTADO ACTUAL
========================== --}}
<div
    class="relative overflow-hidden rounded-2xl border {{ $estadoBanner['border'] }} bg-gradient-to-r {{ $estadoBanner['wash'] }} to-transparent mt-5 p-4 sm:p-5 flex justify-between items-center">

    <div class="flex items-center gap-4">

        {{-- Icono --}}
        <span
            class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-xl sm:text-2xl text-white {{ $estadoBanner['iconBg'] }} {{ $estadoBanner['iconSh'] }} {{ $estadoBanner['pulse'] ? 'anillo-vivo' : '' }} shrink-0">

            <i class="bi {{ $estadoInfo['icon'] }}"></i>

        </span>

        {{-- Información --}}
        <div>

            <p class="uppercase text-slate-400 mb-1 tracking-[1px] text-[0.65rem]">
                Estado actual
            </p>

            <p class="font-bold text-lg sm:text-xl mb-0 leading-none {{ $estadoInfo['texto'] }}">
                {{ $estadoInfo['label'] }}
            </p>

            <p class="text-slate-400 text-sm mb-0 mt-1">
                {{ $estadoInfo['desc'] }}
            </p>

        </div>

    </div>

</div>