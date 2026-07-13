@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-clock-history text-info me-2"></i>Panel de Control: Asistencias
        </h2>
        <div class="d-flex gap-2">
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-outline-primary shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalRegistrarBecario">
                <i class="bi bi-person-plus"></i> + Nuevo Becario
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-dark border-secondary shadow rounded-4">
            <div class="card-body">
                <div class="text-secondary small">
                    Becarios Activos
                </div>
                <h2 id="card-activos"
                    class="text-success fw-bold mb-0">
                    0
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark border-secondary shadow rounded-4">
            <div class="card-body">
                <div class="text-secondary small">
                    En Descanso
                </div>
                <h2 id="card-descanso"
                    class="text-info fw-bold mb-0">
                    0
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark border-secondary shadow rounded-4">
            <div class="card-body">
                <div class="text-secondary small">
                    Turnos Finalizados
                </div>
                <h2 id="card-finalizados"
                    class="text-warning fw-bold mb-0">
                    0
                </h2>
            </div>
        </div>
    </div>

    <div class="card bg-dark border-secondary shadow-lg rounded-4">
        <div class="card-body p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="min-width: 800px;">
                    <thead>
                        <tr class="text-secondary" style="background-color: rgba(255,255,255,0.03);">
                            <th class="ps-4 py-3 fw-semibold text-uppercase small">Becario</th>
                            <th class="py-3 fw-semibold text-uppercase small">Fecha</th>
                            <th class="py-3 fw-semibold text-uppercase small">Entrada</th>
                            <th class="py-3 fw-semibold text-uppercase small">Salida</th>
                            <th class="py-3 fw-semibold text-uppercase small">Pausas</th>
                            <th class="py-3 fw-semibold text-uppercase small">Tiempo Total</th>
                            <th class="py-3 fw-semibold text-uppercase small">Horas Extras</th>
                            <th class="py-3 fw-semibold text-uppercase small">Estado</th>
                            <!-- Acciones eliminadas de aquí -->
                        </tr>
                    </thead>
                    <tbody>
    @foreach($asistencias as $a)
<tr data-user="{{ $a->user_id }} "data-id="{{ $a->id }}">
    <td class="ps-4 py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-secondary bg-opacity-25 border border-secondary d-flex align-items-center justify-content-center text-info fw-bold"
                 style="width:36px;height:36px;font-size:0.9rem;">
                {{ strtoupper(substr($a->user->name, 0, 1)) }}
            </div>
            <span class="text-white fw-bold">{{ $a->user->name }}</span>
        </div>
    </td>
    <td class="py-3">
        {{ $a->fecha ? \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') : 'N/A' }}
    </td>
    <td class="py-3">
        <span id="entrada-{{ $a->id }}" class="badge rounded-pill text-bg-success bg-opacity-25 text-success px-3 py-2">
            <i class="bi bi-box-arrow-in-right me-1"></i>{{ $a->hora_entrada ? \Carbon\Carbon::parse($a->hora_entrada)->format('h:i A') : '--:--' }}
        </span>
    </td>
    <td class="py-3">
        <span id="salida-{{ $a->id }}" class="badge rounded-pill text-bg-danger bg-opacity-25 text-danger px-3 py-2">
            <i class="bi bi-box-arrow-left me-1"></i>{{ $a->hora_salida ? \Carbon\Carbon::parse($a->hora_salida)->format('h:i A') : '---' }}
        </span>
    </td>
    <td class="py-3">
        <span id="pausas-{{ $a->id }}" class="badge rounded-pill text-bg-warning bg-opacity-25 text-warning px-3 py-2">
            <i class="bi bi-cup-hot me-1"></i>{{ $a->tiempoPausas() }}
        </span>
    </td>
    <td class="py-3">
        <span id="trabajado-{{ $a->id }}" class="badge rounded-pill text-bg-info bg-opacity-25 text-info px-3 py-2">
            <i class="bi bi-stopwatch me-1"></i>{{ $a->formatoTiempo($a->tiempoTrabajado()) }}
        </span>
    </td>

<td class="py-3">
    <div 
        id="extras-{{ $a->id }}"
        class="badge rounded-pill text-bg-primary bg-opacity-25 text-primary px-3 py-2 text-start">
        <div>
            <i class="bi bi-alarm me-1"></i>
                <strong>Total:</strong>{{ $a->horasExtrasTotalFormato() }}
        </div>
        <small class="d-block mt-1">
            <i class="bi bi-box-arrow-in-right me-1"></i>Entrada:{{ $a->horasExtrasEntradaFormato() }}
        </small>
        <small class="d-block">
            <i class="bi bi-box-arrow-left me-1"></i>Salida:{{ $a->horasExtrasSalidaFormato() }}
        </small>
    </div>
</td>

    <td class="py-3">
        <span id="estado-{{ $a->id }}"
              class="badge rounded-pill px-3 py-2 {{ $a->hora_salida ? 'text-bg-secondary' : ($a->user->pausas->isNotEmpty() ? 'text-bg-info text-dark' : 'text-bg-success') }}">
            {{ $a->hora_salida ? 'Turno terminado' : ($a->user->pausas->isNotEmpty() ? 'En descanso' : 'Activo') }}
        </span>
    </td>
</tr>
@endforeach
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.modals.registrar-becario')

<script>
let estadoAsistencias = {}; // guarda el estado de cada fila entre sincronizaciones

function formatoHMS(segundos) {
    segundos = Math.max(0, Math.floor(segundos));
    const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
    const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
    const s = String(segundos % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
}

function actualizarExtras(id, e)
{

    const el = document.getElementById(
        'extras-'+id
    );


    if(el){

        el.innerHTML = `

        <div>
            <i class="bi bi-alarm me-1"></i>
            <strong>Total:</strong>
            ${formatoHMS(e.extras)}
        </div>

        <small class="d-block mt-1">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            Entrada:
            ${formatoHMS(e.extrasEntrada)}
        </small>

        <small class="d-block">
            <i class="bi bi-box-arrow-left me-1"></i>
            Salida:
            ${formatoHMS(e.extrasSalida)}
        </small>

        `;

    }

}

function actualizarTarjetas(data)
{

    let activos = 0;
    let descanso = 0;
    let finalizados = 0;


    data.forEach(a => {


        if(a.turno_terminado){

            finalizados++;

        }
        else if(a.en_pausa){

            descanso++;

        }
        else{

            activos++;

        }


    });


    const cardActivos =
        document.getElementById('card-activos');


    const cardDescanso =
        document.getElementById('card-descanso');


    const cardFinalizados =
        document.getElementById('card-finalizados');



    if(cardActivos){

        cardActivos.textContent = activos;

    }


    if(cardDescanso){

        cardDescanso.textContent = descanso;

    }


    if(cardFinalizados){

        cardFinalizados.textContent = finalizados;

    }


}

function crearFila(a) {
    const tr = document.createElement('tr');
tr.setAttribute('data-user', a.user_id);
tr.setAttribute('data-id', a.id);
    tr.innerHTML = `
        <td class="ps-4 py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-secondary bg-opacity-25 border border-secondary d-flex align-items-center justify-content-center text-info fw-bold" style="width:36px;height:36px;font-size:0.9rem;">
                    ${a.user_inicial}
                </div>
                <span class="text-white fw-bold">${a.user_name}</span>
            </div>
        </td>
        <td class="py-3">${a.fecha}</td>
        <td class="py-3"><span id="entrada-${a.id}" class="badge rounded-pill text-bg-success bg-opacity-25 text-success px-3 py-2"><i class="bi bi-box-arrow-in-right me-1"></i>${a.hora_entrada}</span></td>
        <td class="py-3"><span id="salida-${a.id}" class="badge rounded-pill text-bg-danger bg-opacity-25 text-danger px-3 py-2"><i class="bi bi-box-arrow-left me-1"></i>${a.hora_salida}</span></td>
        <td class="py-3"><span id="pausas-${a.id}" class="badge rounded-pill text-bg-warning bg-opacity-25 text-warning px-3 py-2"><i class="bi bi-cup-hot me-1"></i>${formatoHMS(a.pausas_segundos)}</span></td>
        <td class="py-3"><span id="trabajado-${a.id}" class="badge rounded-pill text-bg-info bg-opacity-25 text-info px-3 py-2"><i class="bi bi-stopwatch me-1"></i>${formatoHMS(a.trabajado_segundos)}</span></td>
        <td class="py-3"><span id="extras-${a.id}" class="badge rounded-pill text-bg-primary bg-opacity-25 text-primary px-3 py-2"><i class="bi bi-alarm me-1"></i>${formatoHMS(a.extras_segundos)}</span></td>
        <td class="py-3"><span id="estado-${a.id}" class="badge rounded-pill px-3 py-2 ${a.estado.clase}">${a.estado.texto}</span></td>
    `;
    return tr;
}

function sincronizar() {
    fetch("{{ route('admin.tiempos') }}")
        .then(res => res.json())
        .then(data => {
    const usuariosServidor = [];

    actualizarTarjetas(data);


    const tbody = document.querySelector('table tbody');


    data.forEach(a => {
        usuariosServidor.push(String(a.user_id));
                estadoAsistencias[a.id] = {
    trabajado: a.trabajado_segundos,
    pausas: a.pausas_segundos,
    extras: a.extras_segundos,
    extrasEntrada: a.extras_entrada_segundos,
    extrasSalida: a.extras_salida_segundos,
    enPausa: a.en_pausa,
    turnoTerminado: a.turno_terminado,
    extrasCreciendo: a.extras_creciendo,
};

                let fila = document.querySelector(
    `tr[data-user="${a.user_id}"]`
);

if (!fila) {

    fila = crearFila(a);

    tbody.prepend(fila);

}
else if (
    fila.dataset.id != a.id
) {

    const nuevaFila = crearFila(a);

    fila.replaceWith(
        nuevaFila
    );

    fila = nuevaFila;

}
                fila.setAttribute('data-id',a.id);

                actualizarTexto('entrada-' + a.id, 'bi-box-arrow-in-right', a.hora_entrada);
                actualizarTexto('salida-' + a.id, 'bi-box-arrow-left', a.hora_salida);

                const estadoEl = document.getElementById('estado-' + a.id);
                if (estadoEl) {
                    estadoEl.className = 'badge rounded-pill px-3 py-2 ' + a.estado.clase;
                    estadoEl.textContent = a.estado.texto;
                }
            });
            tbody.querySelectorAll('tr').forEach(fila => {

    if(
        !usuariosServidor.includes(
            fila.dataset.user
        )
    ){

        delete estadoAsistencias[
            fila.dataset.id
        ];

        fila.remove();

    }

});

        })
        .catch(err => console.error('Error sincronizando asistencias:', err));
}

function actualizarTexto(id, icono, texto)
{
    const el = document.getElementById(id);

    if(!el) return;

    el.innerHTML = `
        <i class="bi ${icono} me-1"></i>${texto}
    `;
}

function tick() {
    Object.keys(estadoAsistencias).forEach(id => {
        const e = estadoAsistencias[id];
        if (e.turnoTerminado) return;

        if (e.enPausa) {
            e.pausas += 1;
        } else {
            e.trabajado += 1;
        }

        if (e.extrasCreciendo) {
        e.extras += 1;
        e.extrasSalida += 1;
        }

        actualizarTexto('pausas-' + id, 'bi-cup-hot', formatoHMS(e.pausas));
        actualizarTexto('trabajado-' + id, 'bi-stopwatch', formatoHMS(e.trabajado));
       actualizarExtras(id,e);
    });
}

sincronizar();
setInterval(sincronizar, 10000); // corrige con el servidor cada 10s
setInterval(tick, 1000);         // avanza el reloj visual cada 1s
</script>
@endsection