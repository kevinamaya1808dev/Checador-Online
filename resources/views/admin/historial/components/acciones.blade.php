<div class="rounded-2xl bg-slate-800/50 border border-white/[0.08] p-4">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">

        {{-- Botón regresar --}}
        <div class="lg:col-span-3">
            <a
                href="{{ route('admin.historial') }}"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-slate-500 text-slate-300 px-4 py-2 text-sm font-medium hover:bg-white/5 hover:border-slate-400 transition-colors"
            >
                <i class="bi bi-arrow-left"></i>
                Regresar al historial
            </a>
        </div>

        {{-- Periodo --}}
        <div class="lg:col-span-5">
            <form class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                <input
                    type="date"
                    class="w-full rounded-lg bg-slate-950 border border-slate-600 text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                <input
                    type="date"
                    class="w-full rounded-lg bg-slate-950 border border-slate-600 text-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </form>
        </div>

        {{-- Acciones --}}
        <div class="lg:col-span-4">
            <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">

                <a href="{{ route('admin.reportes.excel', $user) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 transition-colors">
                    <i class="bi bi-file-earmark-excel"></i>
                    Exportar Excel
                </a>

                <a href="{{ route('admin.reportes.pdf.becario', $user) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 transition-colors">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Exportar PDF
                </a>

            </div>
        </div>

    </div>

</div>