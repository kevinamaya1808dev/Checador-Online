@props(['route', 'icon', 'label', 'collapsed', 'open'])

<a href="{{ route($route) }}"
   class="flex items-center mx-2 my-1 rounded-xl transition-all duration-300 group
          {{ request()->routeIs($route) 
             ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' 
             : 'text-gray-600 hover:bg-[#EAE4D8] dark:text-gray-400 dark:hover:bg-white/10 dark:hover:text-white' }}"
   
   {{-- Lógica: Si está colapsado y no abierto, centramos. Si no, padding normal --}}
   :class="{ 'justify-center p-3': {{ $collapsed }} && !{{ $open }}, 'px-4 py-3': !({{ $collapsed }} && !{{ $open }}) }"
>
    <i class="bi {{ $icon }} text-xl shrink-0"></i>

    <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300"
          x-show="!{{ $collapsed }} || {{ $open }}">
        {{ $label }}
    </span>
</a>