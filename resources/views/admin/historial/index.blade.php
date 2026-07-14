@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <x-history.header />


    <div class="d-flex flex-wrap gap-3 mb-4">

    <a href="{{ route('admin.reportes.pdf.general', request()->query()) }}"
       class="export-btn export-btn-pdf d-inline-flex align-items-center gap-2 rounded-3 px-4 py-2 text-decoration-none fw-semibold text-white">
        <i class="bi bi-file-earmark-pdf-fill fs-6"></i>
        Descargar PDF
    </a>

    <a href="{{ route('admin.reportes.general.excel', request()->query()) }}"
       class="export-btn export-btn-excel d-inline-flex align-items-center gap-2 rounded-3 px-4 py-2 text-decoration-none fw-semibold text-white">
        <i class="bi bi-file-earmark-excel-fill fs-6"></i>
        Exportar Excel
    </a>

</div>

<style>
    .export-btn {
        border: 1px solid transparent;
        font-size: 0.9rem;
        transition: transform 0.15s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .export-btn-pdf {
        background: linear-gradient(180deg, #dc2626, #b91c1c);
        border-color: rgba(248, 113, 113, 0.3);
        box-shadow: 0 3px 10px rgba(153, 27, 27, 0.4);
    }

    .export-btn-pdf:hover,
    .export-btn-pdf:focus-visible {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(153, 27, 27, 0.55);
        filter: brightness(1.08);
    }

    .export-btn-excel {
        background: linear-gradient(180deg, #059669, #047857);
        border-color: rgba(52, 211, 153, 0.3);
        box-shadow: 0 3px 10px rgba(6, 95, 70, 0.4);
    }

    .export-btn-excel:hover,
    .export-btn-excel:focus-visible {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(6, 95, 70, 0.55);
        filter: brightness(1.08);
    }

    .export-btn:active {
        transform: translateY(0) scale(0.97);
    }

    @media (max-width: 576px) {
        .export-btn {
            flex: 1 1 auto;
            justify-content: center;
            font-size: 0.85rem;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    }
</style>


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