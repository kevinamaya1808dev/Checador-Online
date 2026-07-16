@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 py-6">

    <div class="bg-white dark:bg-gray-900 border border-[#EAE4D8] dark:border-gray-700 rounded-2xl shadow-sm p-6">

        @include('admin.historial.components.resumen')
        @include('admin.historial.components.acciones')
        @include('admin.historial.components.tabla')
        
    </div>

</div>

@endsection