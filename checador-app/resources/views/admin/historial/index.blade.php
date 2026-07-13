@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <x-history.header />


   <div class="flex gap-3 mb-4">

    <a 
        href="{{ route('admin.reportes.pdf.general', request()->query()) }}"
        class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition"
    >
        Descargar PDF
    </a>


    <a 
        href="{{ route('admin.reportes.general.excel', request()->query()) }}"
        class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition"
    >
        Exportar Excel
    </a>

</div>



    <x-history.stats
        :asistencias="$asistencias"
    />


    <x-history.search
        :meses="$meses"
/>


    <x-history.table
        :asistencias="$asistencias"
    />


    <x-history.pagination
        :asistencias="$asistencias"
    />


</div>

@endsection