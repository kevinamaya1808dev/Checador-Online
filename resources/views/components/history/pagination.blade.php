@if ($asistencias->hasPages())

    <div class="mt-4">

        {{ $asistencias->links('vendor.pagination.ollin') }}

    </div>

@endif