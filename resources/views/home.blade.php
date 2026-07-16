@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
        <h2 class="text-white font-bold mb-0 text-xl sm:text-3xl">
            <i class="bi bi-people-fill text-cyan-400 mr-2"></i>Administración de Usuarios
        </h2>
        
        @include('admin.modals.registrar-becario')
    </div>

    <div class="bg-gray-900 border border-gray-700 shadow-xl rounded-2xl">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-white mb-0 align-middle">
                    <thead>
                        <tr class="text-gray-400 bg-white/[0.03]">
                            <th class="pl-3 sm:pl-4 py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Nombre</th>
                            <th class="py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Email</th>
                            <th class="py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Rol</th>
                            <th class="text-right pr-3 sm:pr-4 py-3 font-semibold uppercase text-[0.8rem] sm:text-xs">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $user)
                        <tr class="hover:bg-white/5">
                            <td class="pl-3 sm:pl-4 py-3 text-[0.8rem] sm:text-base">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-gray-500/25 border border-gray-500 flex items-center justify-center text-cyan-400 font-bold text-[0.75rem] sm:text-[0.9rem] flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-[0.8rem] sm:text-base">
                                <span class="text-cyan-400">{{ $user->email }}</span>
                            </td>
                            <td class="py-3">
                                <span class="inline-flex items-center rounded-full font-medium text-[0.7rem] px-2.5 py-1.5 sm:text-xs sm:px-3 sm:py-2
                                    @if(strtolower($user->role) === 'admin') bg-blue-500/25 text-blue-400
                                    @else bg-gray-500/25 text-gray-300
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-right pr-3 sm:pr-4 py-3">
                                <div class="flex justify-end gap-2 flex-nowrap">
                                    
                                    <button type="button"
                                            class="w-7 h-7 sm:w-[34px] sm:h-[34px] rounded-full p-0 inline-flex items-center justify-center border-none text-white text-[0.75rem] sm:text-[0.95rem] transition-all duration-200 bg-gradient-to-br from-cyan-400 to-cyan-600 shadow-[0_2px_8px_rgba(8,145,178,0.45)] hover:text-white hover:-translate-y-[3px] hover:scale-[1.06] hover:shadow-[0_6px_16px_rgba(8,145,178,0.6)] hover:brightness-110 focus:text-white focus:-translate-y-[3px] focus:scale-[1.06] focus:shadow-[0_6px_16px_rgba(8,145,178,0.6)] focus:brightness-110 active:translate-y-0 active:scale-[0.96]"
                                            title="Editar"
                                            onclick="prepararEdicion('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->role }}', '{{ $user->email }}')">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    @if($user->id != 1)
                                    <form action="{{ route('users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-7 h-7 sm:w-[34px] sm:h-[34px] rounded-full p-0 inline-flex items-center justify-center border-none text-white text-[0.75rem] sm:text-[0.95rem] transition-all duration-200 bg-gradient-to-br from-red-400 to-red-600 shadow-[0_2px_8px_rgba(220,38,38,0.45)] hover:text-white hover:-translate-y-[3px] hover:scale-[1.06] hover:shadow-[0_6px_16px_rgba(220,38,38,0.6)] hover:brightness-110 focus:text-white focus:-translate-y-[3px] focus:scale-[1.06] focus:shadow-[0_6px_16px_rgba(220,38,38,0.6)] focus:brightness-110 active:translate-y-0 active:scale-[0.96]"
                                                title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.modals.editar-usuario')

@endsection

<script>
    // 1. Función global para abrir cualquier modal
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
        }
    };

    // 2. Lógica para cerrar modales (se ejecuta al cargar la página)
    document.addEventListener('DOMContentLoaded', () => {
        const closeButtons = document.querySelectorAll('.btn-close-modal');
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('[role="dialog"]');
                if (modal) {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    modal.classList.remove('opacity-100', 'pointer-events-auto');
                }
            });
        });
    });
</script>