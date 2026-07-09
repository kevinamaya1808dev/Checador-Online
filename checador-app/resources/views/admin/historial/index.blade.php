@extends('layouts.app')

@section('content')

<div class="container-fluid">

    @include('admin.historial.header')

    @include('admin.historial.stats')

    @include('admin.historial.search')

    @include('admin.historial.filters')

    @include('admin.historial.table')

    @include('admin.historial.pagination')

</div>

@endsection