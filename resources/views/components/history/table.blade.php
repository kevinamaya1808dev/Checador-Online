<div class="bg-gray-900 border border-gray-700 rounded-2xl shadow-xl">

    <div class="flex justify-between items-center px-5 py-3 border-b border-gray-700">

        <h5 class="mb-0 text-white font-semibold text-lg">
            <i class="bi bi-table mr-2"></i>
            Registros
        </h5>

        <span class="inline-flex items-center rounded-full bg-gray-600 text-white text-sm px-3 py-1">

            {{ $asistencias->total() }}

            registros

        </span>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-white align-middle mb-0">

            <thead>

<tr class="bg-white/[0.03] text-gray-400">

    <th class="px-4 py-3 text-left text-xs uppercase font-semibold">Becario</th>

    <th class="px-4 py-3 text-left text-xs uppercase font-semibold">Fecha</th>

   <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-semibold">Entrada</th>

    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-semibold">Salida</th>

    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-semibold">Pausas</th>

    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-semibold">Tiempo pausa</th>

    <th class="px-4 py-3 text-left text-xs uppercase font-semibold">Tiempo trabajado</th>

    <th class="hidden md:table-cell px-4 py-3 text-left text-xs uppercase font-semibold">Horas extra</th>

    <th class="px-4 py-3 text-left text-xs uppercase font-semibold">Acciones</th>

</tr>

</thead>
            <tbody>

                @forelse($asistencias as $asistencia)

                    <x-history.row
                        :asistencia="$asistencia"
                    />

                @empty

                    <tr>

                        <td colspan="9" class="text-center py-10 text-gray-400">

                            <i class="bi bi-folder-x text-4xl"></i>

                            <br><br>

                            No existen registros.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
    @include('components.history.modal')

</div>