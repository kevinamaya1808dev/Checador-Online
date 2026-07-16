<div class="entrada bg-slate-900/85 backdrop-blur-[15px] border border-white/[0.08] rounded-3xl p-5 sm:p-6 h-full shadow-lg" style="animation-delay:.08s">

    <p class="uppercase text-slate-500 font-bold tracking-[2px] text-[0.7rem] mb-1">
        Tu turno en tiempo real
    </p>

    {{-- =========================
         RELOJ ANALÓGICO
    ========================== --}}
    <div class="relative w-[250px] h-[250px] mx-auto mt-3">

        <div class="absolute inset-0 rounded-full bg-blue-500/20 blur-3xl scale-90"></div>

        <div class="relative w-full h-full rounded-full border-8 border-[#001f3f] bg-gradient-to-b from-[#00172e] to-[#000d1a] shadow-[inset_0_0_30px_#000,0_0_40px_rgba(0,31,63,0.9)]">

            {{-- Números --}}
            <span class="absolute font-mono text-xs font-bold text-blue-400/60 top-3 left-1/2 -translate-x-1/2">
                12
            </span>

            <span class="absolute font-mono text-xs font-bold text-blue-400/60 bottom-3 left-1/2 -translate-x-1/2">
                6
            </span>

            <span class="absolute font-mono text-xs font-bold text-blue-400/60 left-3 top-1/2 -translate-y-1/2">
                9
            </span>

            <span class="absolute font-mono text-xs font-bold text-blue-400/60 right-3 top-1/2 -translate-y-1/2">
                3
            </span>

            {{-- Centro --}}
            <div
                class="absolute top-1/2 left-1/2 w-3.5 h-3.5 -mt-[7px] -ml-[7px] bg-blue-500 rounded-full z-[6] shadow-[0_0_15px_#3b82f6]">
            </div>

            {{-- Manecillas --}}
            <div
                id="reloj-hora"
                class="absolute bottom-1/2 left-1/2 origin-bottom rounded w-1.5 h-[58px] -ml-[3px] bg-gradient-to-b from-blue-600 to-blue-300 z-[2]">
            </div>

            <div
                id="reloj-min"
                class="absolute bottom-1/2 left-1/2 origin-bottom rounded w-1 h-[85px] -ml-[2px] bg-gradient-to-b from-cyan-400 to-white z-[3]">
            </div>

            <div
                id="reloj-seg"
                class="absolute bottom-1/2 left-1/2 origin-bottom rounded-full w-[2px] h-[95px] -ml-px bg-red-400/80 z-[4]">
            </div>

        </div>

    </div>

    {{-- =========================
         RELOJ DIGITAL
    ========================== --}}
    <div class="text-center mt-5">

        <div
            id="reloj-digital"
            class="text-4xl sm:text-5xl font-bold font-mono tracking-tight bg-gradient-to-b from-white to-slate-300 bg-clip-text text-transparent">

            --:--:--

        </div>

        <p
            id="reloj-fecha"
            class="inline-block text-slate-400 text-xs mt-1.5 mb-0 capitalize border border-white/10 rounded-full px-3 py-1">

        </p>

    </div>

</div>