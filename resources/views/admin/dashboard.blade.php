@extends('layouts.app')
@section('content')
<div class="w-full px-3 md:px-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-center sm:text-left mb-4 gap-2">
        <h2 class="font-bold mb-0 text-lg sm:text-xl lg:text-3xl">
            <i class="bi bi-clock-history text-cyan-400 mr-2"></i>Panel de Control: Asistencias
        </h2>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-4">

        <div class="bg-gray-900 border border-gray-700 shadow-lg rounded-2xl h-full">
            <div class="px-2 sm:px-3 py-2 sm:py-3">
                <div class="text-gray-400 text-[0.68rem] sm:text-sm">Becarios Activos</div>
                <h2 id="card-activos" class="text-green-500 font-bold mb-0 text-lg sm:text-2xl">0</h2>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-700 shadow-lg rounded-2xl h-full">
            <div class="px-2 sm:px-3 py-2 sm:py-3">
                <div class="text-gray-400 text-[0.68rem] sm:text-sm">En Descanso</div>
                <h2 id="card-descanso" class="text-cyan-400 font-bold mb-0 text-lg sm:text-2xl">0</h2>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-700 shadow-lg rounded-2xl h-full">
            <div class="px-2 sm:px-3 py-2 sm:py-3">
                <div class="text-gray-400 text-[0.68rem] sm:text-sm">Turnos Finalizados</div>
                <h2 id="card-finalizados" class="text-yellow-400 font-bold mb-0 text-lg sm:text-2xl">0</h2>
            </div>
        </div>

        <div class="col-span-3">
            <div class="bg-gray-900 border border-gray-700 shadow-xl rounded-2xl">
                <div class="p-0 overflow-hidden">

                    {{-- Tabla (tablet / desktop) --}}
                    <div class="overflow-x-auto hidden md:block">
                        <table class="w-full text-white mb-0 align-middle" style="min-width: 800px;">
                            <thead>
                                <tr class="text-gray-400 bg-white/[0.03]">
                                    <th class="pl-4 py-3 font-semibold uppercase text-xs">Becario</th>
                                    <th class="py-3 font-semibold uppercase text-xs">Fecha</th>
                                    <th class="py-3 font-semibold uppercase text-xs">Entrada</th>
                                    <th class="py-3 font-semibold uppercase text-xs">Salida</th>
                                    <th class="py-3 font-semibold uppercase text-xs">Pausas</th>
                                    <th class="py-3 font-semibold uppercase text-xs">Tiempo Total</th>
                                    <th class="py-3 font-semibold uppercase text-xs">Horas Extras</th>
                                    <th class="py-3 font-semibold uppercase text-xs">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-asistencias">
                                <tr id="tabla-vacia" class="hover:bg-white/5">
                                    <td colspan="8" class="text-center text-gray-400 py-10">
                                        <i class="bi bi-clock-history text-2xl block mb-2"></i>
                                        No existen asistencias activas actualmente
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Tarjetas (móvil) --}}
                    <div id="tarjetas-asistencias" class="block md:hidden p-3">
                        <p id="tarjetas-vacio" class="text-center text-gray-400 py-4 mb-0">
                            <i class="bi bi-clock-history text-2xl block mb-2"></i>
                            No existen asistencias activas actualmente
                        </p>
                    </div>

                    <div class="hidden md:block lg:hidden text-center text-gray-400 text-xs py-2 border-t border-gray-700 bg-white/[0.02]">
                        <i class="bi bi-arrow-left-right mr-1"></i>Desliza para ver toda la tabla
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- Solo configuración: las rutas Blade no pueden vivir en el .js externo,
     así que se exponen aquí como datos globales antes de cargar el script. --}}
<script>
    window.RUTAS = {
        tiempos: "{{ route('admin.tiempos') }}"
    };
</script>
<script src="{{ asset('js/admin/dashboard.js') }}" defer></script>
@endsection