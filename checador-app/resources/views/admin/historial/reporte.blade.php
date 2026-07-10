@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card bg-dark border-secondary shadow">

        @include('admin.historial.components.resumen')
        @include('admin.historial.components.tabla')
    </div>

</div>

@endsection