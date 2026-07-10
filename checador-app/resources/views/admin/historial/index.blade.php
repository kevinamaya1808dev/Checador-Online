@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <x-history.header />

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