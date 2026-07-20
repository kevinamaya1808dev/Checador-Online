<div class="rounded-2xl bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 shadow-sm overflow-hidden">
<div class="px-4 py-3 border-b border-[#EAE4D8] dark:border-gray-700 bg-[#F5F2EA] dark:bg-gray-800 rounded-t-2xl">
    <h5 class="font-bold text-base mb-0 text-gray-800 dark:text-white">
        <ion-icon name="calendar-outline" class="mr-2 text-blue-600 dark:text-blue-400"></ion-icon>
        Historial de jornadas
    </h5>
</div>

    {{-- Tabla normal (tablet / desktop) --}}
    <div class="hidden md:block overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead>
                <tr class="bg-[#F5F2EA] dark:bg-gray-800 border-b border-[#EAE4D8] dark:border-gray-700 text-gray-600 dark:text-gray-400 uppercase text-xs tracking-wide">
                    <th class="px-4 py-3 font-semibold">Fecha</th>
                    <th class="px-4 py-3 font-semibold">Entrada</th>
                    <th class="px-4 py-3 font-semibold">Salida</th>
                    <th class="px-4 py-3 font-semibold">Pausas</th>
                    <th class="px-4 py-3 font-semibold">Tiempo pausa</th>
                    <th class="px-4 py-3 font-semibold">Tiempo trabajado</th>
                    <th class="px-4 py-3 font-semibold">Horas extra</th>
                </tr>
            </thead>

            <tbody>
            @forelse($asistencias as $asistencia)
                <tr class="border-b border-[#EAE4D8] dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ $asistencia->hora_entrada
                            ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                            : '--' }}
                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ $asistencia->hora_salida
                            ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                            : '--' }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center rounded-md bg-sky-100 dark:bg-sky-900/20 text-sky-700 dark:text-sky-300 text-xs font-semibold px-2 py-1">
                            {{ $asistencia->pausas->count() }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                        {{ $asistencia->tiempoPausas() }}
                    </td>

                    <td class="px-4 py-3 font-mono text-gray-800 dark:text-gray-200">
                        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}
                    </td>

                    <td class="px-4 py-3">
                        @if($asistencia->tiempoHorasExtras())
                            <span class="text-emerald-700 dark:text-emerald-400 font-bold">
                                {{ $asistencia->horasExtrasTotalFormato() }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-600">
                                00:00:00
                            </span>
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                        No existen registros.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>

    </div>

    {{-- Tarjetas (móvil) --}}
    <div class="block md:hidden p-3 space-y-3">

        @forelse($asistencias as $asistencia)

            <div class="rounded-xl bg-white dark:bg-gray-800/50 border border-[#EAE4D8] dark:border-gray-700 overflow-hidden">

                <div class="flex justify-between items-center px-4 py-2.5 border-b border-[#EAE4D8] dark:border-gray-700 font-semibold text-gray-900 dark:text-white">
                    <span>
                        <ion-icon name="calendar-outline" class="text-blue-600 dark:text-blue-400"></ion-icon>
                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                    </span>
                    <span class="text-sm font-semibold rounded-md bg-emerald-100 dark:bg-emerald-600/20 text-emerald-700 dark:text-emerald-300 px-2.5 py-1">
                        {{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}
                    </span>
                </div>

                <div class="grid grid-cols-2">

                    <div class="px-4 py-2.5 border-r border-b border-[#EAE4D8] dark:border-gray-700">
                        <p class="m-0 text-xs text-gray-500 dark:text-slate-400">Entrada</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            {{ $asistencia->hora_entrada
                                ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A')
                                : '--' }}
                        </p>
                    </div>

                    <div class="px-4 py-2.5 border-b border-[#EAE4D8] dark:border-gray-700">
                        <p class="m-0 text-xs text-gray-500 dark:text-slate-400">Salida</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            {{ $asistencia->hora_salida
                                ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A')
                                : '--' }}
                        </p>
                    </div>

                    <div class="px-4 py-2.5 border-r border-[#EAE4D8] dark:border-gray-700">
                        <p class="m-0 text-xs text-gray-500 dark:text-slate-400">Pausas</p>
                        <p class="mt-0.5 mb-0 text-sm text-gray-800 dark:text-white">
                            {{ $asistencia->pausas->count() }}
                            <span class="text-xs text-gray-400 dark:text-slate-500">
                                ({{ $asistencia->tiempoPausas() }})
                            </span>
                        </p>
                    </div>

                    <div class="px-4 py-2.5">
                        <p class="m-0 text-xs text-gray-500 dark:text-slate-400">Horas extra</p>
                        <p class="mt-0.5 mb-0 text-sm {{ $asistencia->tiempoHorasExtras() ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-gray-600' }}">
                            @if($asistencia->tiempoHorasExtras())
                                {{ $asistencia->horasExtrasTotalFormato() }}
                            @else
                                00:00:00
                            @endif
                        </p>
                    </div>

                </div>

            </div>

        @empty

            <p class="text-center text-gray-500 dark:text-gray-400 py-6 mb-0">
                No existen registros.
            </p>

        @endforelse

    </div>

</div>