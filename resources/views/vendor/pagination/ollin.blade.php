{{-- vendor/pagination/ollin.blade.php --}}
@if ($paginator->hasPages())
<nav role="navigation" aria-label="Paginación" class="flex flex-col sm:flex-row items-center justify-between gap-3">

    {{-- Texto de resultados --}}
    <div>
        <p class="text-gray-400 text-sm mb-0">
            Mostrando
            <span class="font-semibold text-white">{{ $paginator->firstItem() }}</span>
            a
            <span class="font-semibold text-white">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-semibold text-white">{{ $paginator->total() }}</span>
            resultados
        </p>
    </div>

    <ul class="flex items-center gap-1 mb-0 list-none p-0">

        {{-- Botón Anterior --}}
        @if ($paginator->onFirstPage())
            <li>
                <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-transparent border border-white/[0.08] text-gray-500">
                    <i class="bi bi-chevron-left"></i>
                </span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white/[0.03] border border-white/15 text-slate-300 transition-all duration-200 hover:bg-gradient-to-br hover:from-cyan-400 hover:to-blue-900 hover:border-blue-900 hover:text-white hover:-translate-y-0.5">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)

            {{-- "..." --}}
            @if (is_string($element))
                <li>
                    <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm border-0 bg-transparent text-slate-300">{{ $element }}</span>
                </li>
            @endif

            {{-- Array de páginas --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li>
                            <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm font-semibold bg-gradient-to-br from-blue-500 to-blue-900 border border-blue-900 text-white shadow-[0_2px_10px_rgba(30,58,138,0.55)]">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}"
                               class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white/[0.03] border border-white/15 text-slate-300 transition-all duration-200 hover:bg-gradient-to-br hover:from-cyan-400 hover:to-blue-900 hover:border-blue-900 hover:text-white hover:-translate-y-0.5">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Botón Siguiente --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white/[0.03] border border-white/15 text-slate-300 transition-all duration-200 hover:bg-gradient-to-br hover:from-cyan-400 hover:to-blue-900 hover:border-blue-900 hover:text-white hover:-translate-y-0.5">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        @else
            <li>
                <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-transparent border border-white/[0.08] text-gray-500">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </li>
        @endif

    </ul>
</nav>
@endif