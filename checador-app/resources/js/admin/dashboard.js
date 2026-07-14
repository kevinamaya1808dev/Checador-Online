(function () {
    'use strict';

    // ---- Configuración ----
    const POLLING_MS = 1500; // frecuencia de sincronización con el servidor
    const TICK_MS = 1000;    // frecuencia del reloj visual (avance local)

    // ---- Estado en memoria ----
    let estadoAsistencias = {}; // keyed por user_id
    let intervaloSincronizacion = null;

    // ---- Formato ----
    function formatoHMS(segundos) {
        segundos = Math.max(0, Math.floor(segundos));
        const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
        const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
        const s = String(segundos % 60).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    // ---- Helpers de render ----
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

    function renderTablaVacia(tbody) {
        tbody.innerHTML = `
            <tr id="tabla-vacia">
                <td colspan="8" class="text-center text-secondary py-5">
                    <i class="bi bi-clock-history fs-3 d-block mb-2"></i>
                    No existen asistencias activas actualmente
                </td>
            </tr>`;
    }

    // ---- Sincronización con el servidor ----
    function sincronizar() {
        fetch(window.RUTAS.tiempos)
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
                        actualizarFila(a);
                    }
                });

                tbody.querySelectorAll('tr[data-user]').forEach(fila => {
                    if (!usuariosServidor.includes(fila.dataset.user)) {
                        delete estadoAsistencias[fila.dataset.user];
                        fila.remove();
                    }
                });

                if (tbody.querySelectorAll('tr[data-user]').length === 0) {
                    renderTablaVacia(tbody);
                }

                actualizarTarjetas(data);
            })
            .catch(err => console.error('Error sincronizando asistencias:', err));
    }

    // ---- Reloj visual local (entre sincronizaciones) ----
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

    // ---- Control de polling (se pausa si la pestaña está oculta) ----
    function iniciarPolling() {
        clearInterval(intervaloSincronizacion);
        intervaloSincronizacion = setInterval(sincronizar, POLLING_MS);
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(intervaloSincronizacion);
        } else {
            sincronizar(); // trae el estado actual de inmediato al volver
            iniciarPolling();
        }
    });

    // ---- Arranque ----
    sincronizar();
    iniciarPolling();
    setInterval(tick, TICK_MS);

})();