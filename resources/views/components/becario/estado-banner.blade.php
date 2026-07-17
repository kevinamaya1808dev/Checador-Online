{{-- Banner de estado --}}
@props(['estadoInfo', 'estadoBanner'])

<div class="relative overflow-hidden rounded-2xl border {{ $estadoBanner['border'] }} dark:border-white/[0.08] bg-white dark:bg-slate-800/40 bg-gradient-to-r {{ $estadoBanner['wash'] }} to-transparent dark:shadow-[0_8px_24px_-8px_rgba(0,0,0,0.5)] mt-5 p-4 sm:p-5 flex justify-between items-center transition-colors duration-300">
    <div class="flex items-center gap-4">
        
        <span class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-xl sm:text-2xl text-white {{ $estadoBanner['iconBg'] }} {{ $estadoBanner['iconSh'] }} {{ $estadoBanner['pulse'] ? 'anillo-vivo' : '' }} shrink-0 transition-all">
            <i class="bi {{ $estadoInfo['icon'] }}"></i>
        </span>

        <div>
            <p class="uppercase text-gray-500 dark:text-slate-500 mb-1 tracking-[1px] text-[0.65rem]">Estado actual</p>
            <p class="font-bold text-lg sm:text-xl mb-0 leading-none {{ $estadoInfo['texto'] }}">{{ $estadoInfo['label'] }}</p>
            <p class="text-gray-600 dark:text-slate-400 text-sm mb-0 mt-1">{{ $estadoInfo['desc'] }}</p>
        </div>

    </div>
</div>