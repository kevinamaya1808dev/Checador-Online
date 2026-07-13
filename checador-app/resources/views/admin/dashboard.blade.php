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

                  <tbody id="tabla-asistencias">
<tr id="tabla-vacia">
    <td colspan="8" class="text-center text-secondary py-5">
        <i class="bi bi-clock-history fs-3 d-block mb-2"></i>
        No existen asistencias activas actualmente
    </td>
</tr>
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.modals.registrar-becario')

<script>
let estadoAsistencias = {}; // ahora keyed por user_id

function formatoHMS(segundos) {
    segundos = Math.max(0, Math.floor(segundos));
    const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
    const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
    const s = String(segundos % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
}

function actualizarTexto(id, icono, texto) {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = `<i class="bi ${icono} me-1"></i>${texto}`;
}

function actualizarExtras(id, e) {
    const el = document.getElementById('extras-' + id);
    if (!el) return;
    el.innerHTML = `
        <div>
            <i class="bi bi-alarm me-1"></i>
            <strong>Total:</strong> ${formatoHMS(e.extras)}
        </div>
        <small class="d-block mt-1">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            Entrada: ${formatoHMS(e.extrasEntrada)}
        </small>
        <small class="d-block">
            <i class="bi bi-box-arrow-left me-1"></i>
            Salida: ${formatoHMS(e.extrasSalida)}
        </small>
    `;
}

function actualizarTarjetas(data) {
    let activos = 0, descanso = 0, finalizados = 0;

    data.forEach(a => {
        if (a.sin_registro) return; // no cuenta en ninguna tarjeta
        if (a.turno_terminado) finalizados++;
        else if (a.en_pausa) descanso++;
        else activos++;
    });

    document.getElementById('card-activos').textContent = activos;
    document.getElementById('card-descanso').textContent = descanso;
    document.getElementById('card-finalizados').textContent = finalizados;
}

function crearFila(a) {
    const tr = document.createElement('tr');
    tr.setAttribute('data-user', a.user_id);
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
        <td class="py-3"><span id="entrada-${a.user_id}" class="badge rounded-pill text-bg-success bg-opacity-25 text-success px-3 py-2"><i class="bi bi-box-arrow-in-right me-1"></i>${a.hora_entrada}</span></td>
        <td class="py-3"><span id="salida-${a.user_id}" class="badge rounded-pill text-bg-danger bg-opacity-25 text-danger px-3 py-2"><i class="bi bi-box-arrow-left me-1"></i>${a.hora_salida}</span></td>
        <td class="py-3"><span id="pausas-${a.user_id}" class="badge rounded-pill text-bg-warning bg-opacity-25 text-warning px-3 py-2"><i class="bi bi-cup-hot me-1"></i>${formatoHMS(a.pausas_segundos)}</span></td>
        <td class="py-3"><span id="trabajado-${a.user_id}" class="badge rounded-pill text-bg-info bg-opacity-25 text-info px-3 py-2"><i class="bi bi-stopwatch me-1"></i>${formatoHMS(a.trabajado_segundos)}</span></td>
        <td class="py-3"><span id="extras-${a.user_id}" class="badge rounded-pill text-bg-primary bg-opacity-25 text-primary px-3 py-2"><i class="bi bi-alarm me-1"></i>${formatoHMS(a.extras_segundos)}</span></td>
        <td class="py-3"><span id="estado-${a.user_id}" class="badge rounded-pill px-3 py-2 ${a.estado.clase}">${a.estado.texto}</span></td>
    `;
    return tr;
}

function actualizarFila(a) {
    actualizarTexto('entrada-' + a.user_id, 'bi-box-arrow-in-right', a.hora_entrada);
    actualizarTexto('salida-' + a.user_id, 'bi-box-arrow-left', a.hora_salida);
    actualizarTexto('pausas-' + a.user_id, 'bi-cup-hot', formatoHMS(a.pausas_segundos));
    actualizarTexto('trabajado-' + a.user_id, 'bi-stopwatch', formatoHMS(a.trabajado_segundos));
    actualizarExtras(a.user_id, {
        extras: a.extras_segundos,
        extrasEntrada: a.extras_entrada_segundos,
        extrasSalida: a.extras_salida_segundos,
    });

    const estado = document.getElementById('estado-' + a.user_id);
    if (estado) {
        estado.className = 'badge rounded-pill px-3 py-2 ' + a.estado.clase;
        estado.textContent = a.estado.texto;
    }
}

function sincronizar() {
    fetch("{{ route('admin.tiempos') }}")
        .then(res => res.json())
        .then(data => {
            const usuariosServidor = [];
            const tbody = document.getElementById('tabla-asistencias');
            const vacio = document.getElementById('tabla-vacia');

            if (vacio && data.length > 0) vacio.remove();

            data.forEach(a => {
                usuariosServidor.push(String(a.user_id));

                estadoAsistencias[a.user_id] = {
                    trabajado: a.trabajado_segundos,
                    pausas: a.pausas_segundos,
                    extras: a.extras_segundos,
                    extrasEntrada: a.extras_entrada_segundos,
                    extrasSalida: a.extras_salida_segundos,
                    enPausa: a.en_pausa,
                    turnoTerminado: a.turno_terminado,
                    extrasCreciendo: a.extras_creciendo,
                    sinRegistro: a.sin_registro,
                };

                let fila = document.querySelector(`tr[data-user="${a.user_id}"]`);

                if (!fila) {
                    fila = crearFila(a);
                    tbody.appendChild(fila);
                } else {
                    actualizarFila(a); // <-- clave: refresca campos aunque el id no cambie
                }
            });

            tbody.querySelectorAll('tr[data-user]').forEach(fila => {
                if (!usuariosServidor.includes(fila.dataset.user)) {
                    delete estadoAsistencias[fila.dataset.user];
                    fila.remove();
                }
            });

            if (tbody.querySelectorAll('tr[data-user]').length === 0) {
                tbody.innerHTML = `
                    <tr id="tabla-vacia">
                        <td colspan="8" class="text-center text-secondary py-5">
                            <i class="bi bi-clock-history fs-3 d-block mb-2"></i>
                            No existen asistencias activas actualmente
                        </td>
                    </tr>`;
            }

            actualizarTarjetas(data);
        })
        .catch(err => console.error('Error sincronizando asistencias:', err));
}

function tick() {
    Object.keys(estadoAsistencias).forEach(userId => {
        const e = estadoAsistencias[userId];
        if (e.turnoTerminado || e.sinRegistro) return;

        if (e.enPausa) e.pausas += 1;
        else e.trabajado += 1;

        if (e.extrasCreciendo) {
            e.extras += 1;
            e.extrasSalida += 1;
        }

        actualizarTexto('pausas-' + userId, 'bi-cup-hot', formatoHMS(e.pausas));
        actualizarTexto('trabajado-' + userId, 'bi-stopwatch', formatoHMS(e.trabajado));
        actualizarExtras(userId, e);
    });
}


let intervaloSincronizacion;

function iniciarPolling() {
    clearInterval(intervaloSincronizacion);
    intervaloSincronizacion = setInterval(sincronizar, 1500);
}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        clearInterval(intervaloSincronizacion);
    } else {
        sincronizar(); // trae el estado actual de inmediato al volver
        iniciarPolling();
    }
});

sincronizar();
iniciarPolling();
setInterval(tick, 1000);


sincronizar();
setInterval(sincronizar, 1500); // cada 1.5s en vez de 10s
setInterval(tick, 1000);         // avanza el reloj visual cada 1s
</script>
@endsection