<div class="grid grid-cols-2 md:grid-cols-12 gap-4">

    {{-- Información del becario --}}
    <div class="col-span-2 md:col-span-6 lg:col-span-4">
        <div class="rounded-2xl bg-slate-800/50 border border-white/[0.08] h-full p-4">
            <h4 class="font-bold text-lg mb-1">
                {{ $user->name }}
            </h4>
            <p class="text-slate-400 text-sm mb-0">
                {{ $user->email }}
            </p>
        </div>
    </div>

    {{-- Jornadas --}}
    <div class="col-span-1 md:col-span-3 lg:col-span-2">
        <div class="rounded-2xl bg-slate-800/50 border border-white/[0.08] h-full p-4 text-center">
            <h3 class="font-bold text-2xl mb-1">
                {{ $resumen['jornadas'] }}
            </h3>
            <small class="text-slate-400 text-xs">
                Jornadas
            </small>
        </div>
    </div>

    {{-- Horas trabajadas --}}
    <div class="col-span-1 md:col-span-3 lg:col-span-2">
        <div class="rounded-2xl bg-slate-800/50 border border-white/[0.08] h-full p-4 text-center">
            <h5 class="font-bold text-lg text-blue-400 mb-1">
                {{ $resumen['horas_trabajadas'] }}
            </h5>
            <small class="text-slate-400 text-xs">
                Trabajadas
            </small>
        </div>
    </div>

    {{-- Tiempo en pausa --}}
    <div class="col-span-1 md:col-span-3 lg:col-span-2">
        <div class="rounded-2xl bg-slate-800/50 border border-white/[0.08] h-full p-4 text-center">
            <h5 class="font-bold text-lg text-amber-400 mb-1">
                {{ $resumen['tiempo_pausa'] }}
            </h5>
            <small class="text-slate-400 text-xs">
                Pausas
            </small>
        </div>
    </div>

    {{-- Horas extra --}}
    <div class="col-span-1 md:col-span-3 lg:col-span-2">
        <div class="rounded-2xl bg-slate-800/50 border border-white/[0.08] h-full p-4 text-center">
            <h5 class="font-bold text-lg text-emerald-400 mb-1">
                {{ $resumen['horas_extra'] }}
            </h5>
            <small class="text-slate-400 text-xs">
                Horas extra
            </small>
        </div>
    </div>

</div>