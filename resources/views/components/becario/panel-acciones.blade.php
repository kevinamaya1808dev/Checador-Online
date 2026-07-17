{{-- resources/views/components/becario/panel-acciones.blade.php --}}
@props(['presenter'])

<div class="flex flex-col gap-3.5">

    {{-- Registrar entrada --}}
    <form action="{{ route('becario.checar') }}" method="POST" class="m-0">
        @csrf
        <x-becario.accion-boton
            color="blue"
            icon="bi-box-arrow-in-right"
            titulo="Registrar entrada"
            descripcion="Inicia el registro de tu turno"
            :disabled="! $presenter->puedeEntrada"
            :destacar="$presenter->destacar('entrada')"
            as="submit"
        />
    </form>

    {{-- Gestionar pausa --}}
    <div>
        <x-becario.accion-boton
            color="amber"
            icon="bi-cup-hot"
            iconRotate="rotate-3"
            titulo="Gestionar pausa"
            descripcion="Justificación obligatoria"
            :disabled="! $presenter->puedePausar"
            :destacar="$presenter->destacar('pausar')"
            as="button"
            onclick="togglePausaMenu()"
        />
        <x-becario.pausa-menu :puede-pausar="$presenter->puedePausar" />
    </div>

    {{-- Finalizar pausa --}}
    <form id="formFinalizarPausa" action="{{ route('becario.finalizarPausa') }}" method="POST" class="m-0">
        @csrf
        <x-becario.accion-boton
            color="blue"
            icon="bi-play-fill"
            titulo="Finalizar pausa"
            descripcion="Reanuda tus actividades"
            :disabled="! $presenter->puedeReanudar"
            :destacar="$presenter->destacar('reanudar')"
            as="button"
            onclick="openModal('modalFinalizarPausa')"
        />
    </form>

    {{-- Registrar salida --}}
    <form id="formSalida" action="{{ route('becario.salida') }}" method="POST" class="m-0">
        @csrf
        <x-becario.accion-boton
            color="red"
            icon="bi-box-arrow-left"
            iconRotate="rotate-3"
            titulo="Registrar salida"
            descripcion="Finaliza tu turno"
            :disabled="! $presenter->puedeSalir"
            :destacar="$presenter->destacar('salir')"
            as="button"
            onclick="openModal('modalSalida')"
        />
    </form>
    <div class="bg-slate-800/50 border border-white/[0.06] rounded-2xl mt-auto p-4 flex gap-3 items-start">
                        <span class="w-8 h-8 rounded-lg bg-blue-500/15 border border-blue-400/25 flex items-center justify-center shrink-0">
                            <i class="bi bi-info-circle-fill text-blue-400 text-sm"></i>
                        </span>
                        <div>
                            <p class="font-bold text-sm mb-0">Recuerda registrar tus pausas</p>
                            <p class="text-slate-400 mb-0 text-[0.75rem]">Para llevar un control correcto de tu tiempo laboral.</p>
                        </div>

</div>

