@props(['asistencias'])

<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">

    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-sm h-full">
        <div class="p-5">

            <small class="text-gray-400">
                Registros
            </small>

            <h3 class="font-bold text-white mt-2">
                {{ $asistencias->total() }}
            </h3>

        </div>
    </div>

    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-sm h-full">
        <div class="p-5">

            <small class="text-gray-400">
                Becarios
            </small>

            <h3 class="font-bold text-blue-500 mt-2">
                {{ $asistencias->pluck('user_id')->unique()->count() }}
            </h3>

        </div>
    </div>

    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-sm h-full">
        <div class="p-5">

            <small class="text-gray-400">
                Finalizados
            </small>

            <h3 class="font-bold text-green-500 mt-2">

                {{ $asistencias->whereNotNull('hora_salida')->count() }}

            </h3>

        </div>
    </div>

    <div class="bg-gray-900 border border-gray-700 rounded-xl shadow-sm h-full">
        <div class="p-5">

            <small class="text-gray-400">
                En curso
            </small>

            <h3 class="font-bold text-yellow-400 mt-2">

                {{ $asistencias->whereNull('hora_salida')->count() }}

            </h3>

        </div>
    </div>

</div>