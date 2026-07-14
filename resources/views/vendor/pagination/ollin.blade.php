@if ($paginator->hasPages())
<nav role="navigation" aria-label="Paginación" class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">

    {{-- Texto de resultados --}}
    <div>
        <p class="text-secondary small mb-0">
            Mostrando
            <span class="fw-semibold text-white">{{ $paginator->firstItem() }}</span>
            a
            <span class="fw-semibold text-white">{{ $paginator->lastItem() }}</span>
            de
            <span class="fw-semibold text-white">{{ $paginator->total() }}</span>
            resultados
        </p>
    </div>

    <ul class="pagination pagination-sm mb-0 gap-1">

        {{-- Botón Anterior --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link pg-link d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-chevron-left"></i>
                </span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link pg-link d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)

            {{-- "..." --}}
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link pg-link border-0 bg-transparent">{{ $element }}</span>
                </li>
            @endif

            {{-- Array de páginas --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link pg-link pg-active fw-semibold">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $url }}" class="page-link pg-link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Botón Siguiente --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link pg-link d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link pg-link d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </li>
        @endif

    </ul>
</nav>

<style>
    .pg-link {
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #cbd5e1;
        min-width: 2.25rem;
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.15s ease;
    }

    .page-item:not(.disabled):not(.active) .pg-link:hover {
        background: linear-gradient(135deg, #22d3ee, #1e3a8a);
        border-color: #1e3a8a;
        color: #fff;
        transform: translateY(-2px);
    }

    .page-item.disabled .pg-link {
        background-color: transparent;
        border-color: rgba(255, 255, 255, 0.08);
        color: #6b7280;
    }

    .pg-active {
        background: linear-gradient(135deg, #3b82f6, #1e3a8a);
        border-color: #1e3a8a;
        color: #fff;
        box-shadow: 0 2px 10px rgba(30, 58, 138, 0.55);
    }

    @media (max-width: 576px) {
        .pg-link {
            min-width: 1.9rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
    }
</style>
@endif