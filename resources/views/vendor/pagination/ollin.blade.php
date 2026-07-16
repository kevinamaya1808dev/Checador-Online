@if ($paginator->hasPages())
<nav role="navigation" aria-label="Paginación" class="flex flex-col sm:flex-row items-center justify-between gap-3">

    {{-- Texto de resultados --}}
    <div>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-0">
            Mostrando
            <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
            a
            <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
            de
            <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
            resultados
        </p>
    </div>

    <ul class="flex items-center gap-1 mb-0 list-none p-0">

        {{-- Botón Anterior --}}
        @if ($paginator->onFirstPage())
            <li>
                <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-transparent border border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-600">
                    <i class="bi bi-chevron-left"></i>
                </span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:text-white hover:-translate-y-0.5">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)

            {{-- "..." --}}
            @if (is_string($element))
                <li>
                    <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm border-0 bg-transparent text-gray-500 dark:text-gray-500">{{ $element }}</span>
                </li>
            @endif

            {{-- Array de páginas --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li>
                            <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm font-semibold bg-blue-600 border border-blue-600 text-white shadow-sm">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}"
                               class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:text-white hover:-translate-y-0.5">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Botón Siguiente --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-gray-800 border border-[#EAE4D8] dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-400 dark:hover:text-white hover:-translate-y-0.5">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        @else
            <li>
                <span class="inline-flex items-center justify-center rounded-lg min-w-[1.9rem] px-2 py-1 text-[0.8rem] sm:min-w-[2.25rem] sm:px-3 sm:py-1.5 sm:text-sm bg-white dark:bg-transparent border border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-600">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </li>
        @endif

    </ul>
</nav>
@endif