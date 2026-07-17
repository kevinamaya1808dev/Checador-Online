(function () {
    'use strict';

    // ---- Configuración ----
    const POLLING_MS = 1750; // frecuencia de sincronización con el servidor
    const TICK_MS = 1000;    // frecuencia del reloj visual (1 segundo para reloj fluido)

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

    // ---- Helpers de render (tabla) ----
    function actualizarTexto(id, icono, texto) {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = `<i class="bi ${icono} mr-1"></i>${texto}`;
    }

    function actualizarExtras(id, e) {
        const el = document.getElementById('extras-' + id);
        if (!el) return;
        el.innerHTML = `
            <div>
                <i class="bi bi-alarm mr-1"></i>
                <strong>Total:</strong> ${formatoHMS(e.extras)}
            </div>
            <small class="block mt-1">
                <i class="bi bi-box-arrow-in-right mr-1"></i>
                Entrada: ${formatoHMS(e.extrasEntrada)}
            </small>
            <small class="block">
                <i class="bi bi-box-arrow-left mr-1"></i>
                Salida: ${formatoHMS(e.extrasSalida)}
            </small>
        `;
    }

    function actualizarTarjetasResumen(data) {
        let activos = 0, descanso = 0, finalizados = 0;

        data.forEach(a => {
            if (a.sin_registro) return;
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
        tr.className = 'hover:bg-white/5';
        tr.setAttribute('data-user', a.user_id);
        tr.innerHTML = `
            <td class="pl-4 py-3">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-gray-500/25 border border-gray-500 flex items-center justify-center text-cyan-400 font-bold text-sm flex-shrink-0">
                        ${a.user_inicial}
                    </div>
                    <span class="text-white font-bold">${a.user_name}</span>
                </div>
            </td>
            <td class="py-3">${a.fecha}</td>
            <td class="py-3"><span id="entrada-${a.user_id}" class="inline-flex items-center rounded-full bg-green-500/25 text-green-400 px-3 py-2 text-sm font-medium"><i class="bi bi-box-arrow-in-right mr-1"></i>${a.hora_entrada}</span></td>
            <td class="py-3"><span id="salida-${a.user_id}" class="inline-flex items-center rounded-full bg-red-500/25 text-red-400 px-3 py-2 text-sm font-medium"><i class="bi bi-box-arrow-left mr-1"></i>${a.hora_salida}</span></td>
            <td class="py-3"><span id="pausas-${a.user_id}" class="inline-flex items-center rounded-full bg-yellow-500/25 text-yellow-400 px-3 py-2 text-sm font-medium"><i class="bi bi-cup-hot mr-1"></i>${formatoHMS(a.pausas_segundos)}</span></td>
            <td class="py-3"><span id="trabajado-${a.user_id}" class="inline-flex items-center rounded-full bg-cyan-500/25 text-cyan-400 px-3 py-2 text-sm font-medium"><i class="bi bi-stopwatch mr-1"></i>${formatoHMS(a.trabajado_segundos)}</span></td>
            <td class="py-3"><span id="extras-${a.user_id}" class="inline-flex items-center rounded-full bg-blue-500/25 text-blue-400 px-3 py-2 text-sm font-medium"><i class="bi bi-alarm mr-1"></i>${formatoHMS(a.extras_segundos)}</span></td>
            <td class="py-3"><span id="estado-${a.user_id}" class="inline-flex items-center rounded-full px-3 py-2 text-sm font-medium ${a.estado.clase}">${a.estado.texto}</span></td>
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
            estado.className = 'inline-flex items-center rounded-full px-3 py-2 text-sm font-medium ' + a.estado.clase;
            estado.textContent = a.estado.texto;
        }
    }

    function renderTablaVacia(tbody) {
        tbody.innerHTML = `
            <tr id="tabla-vacia">
                <td colspan="8" class="text-center text-gray-400 py-10">
                    <i class="bi bi-clock-history text-2xl block mb-2"></i>
                    No existen asistencias activas actualmente
                </td>
            </tr>`;
    }

    // ---- Helpers de render (tarjetas móvil) ----
    function actualizarTextoCard(id, icono, texto) {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = `<i class="bi ${icono} mr-1"></i>${texto}`;
    }

    function actualizarExtrasCard(id, e) {
        const el = document.getElementById('extras-card-' + id);
        if (!el) return;
        el.innerHTML = `
            <i class="bi bi-alarm mr-1"></i>
            <strong>Extras:</strong> ${formatoHMS(e.extras)}
            <span class="mx-1">·</span>
            Ent. ${formatoHMS(e.extrasEntrada)}
            <span class="mx-1">·</span>
            Sal. ${formatoHMS(e.extrasSalida)}
        `;
    }

    function crearTarjeta(a) {
        const div = document.createElement('div');
        div.className = 'bg-white/[0.03] border border-white/10 rounded-xl overflow-hidden';
        div.setAttribute('data-user-card', a.user_id);
        div.innerHTML = `
            <div class="flex items-center justify-between gap-2 px-4 py-3 border-b border-white/10">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gray-500/25 border border-gray-500 flex items-center justify-center text-cyan-400 font-bold text-xs flex-shrink-0">
                        ${a.user_inicial}
                    </div>
                    <span class="text-white font-semibold text-sm whitespace-nowrap overflow-hidden text-ellipsis">${a.user_name}</span>
                </div>
                <span id="estado-card-${a.user_id}" class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ${a.estado.clase}">${a.estado.texto}</span>
            </div>
            <div class="grid grid-cols-2">
                <div class="p-2.5 px-4 border-r border-b border-white/[0.08]">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Entrada</p>
                    <p id="entrada-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-green-500"><i class="bi bi-box-arrow-in-right mr-1"></i>${a.hora_entrada}</p>
                </div>
                <div class="p-2.5 px-4 border-b border-white/[0.08]">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Salida</p>
                    <p id="salida-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-red-500"><i class="bi bi-box-arrow-left mr-1"></i>${a.hora_salida}</p>
                </div>
                <div class="p-2.5 px-4 border-r border-white/[0.08]">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Pausas</p>
                    <p id="pausas-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-yellow-500"><i class="bi bi-cup-hot mr-1"></i>${formatoHMS(a.pausas_segundos)}</p>
                </div>
                <div class="p-2.5 px-4">
                    <p class="m-0 text-[0.72rem] uppercase text-gray-400">Tiempo total</p>
                    <p id="trabajado-card-${a.user_id}" class="mt-0.5 text-[0.85rem] text-cyan-400"><i class="bi bi-stopwatch mr-1"></i>${formatoHMS(a.trabajado_segundos)}</p>
                </div>
            </div>
            <div id="extras-card-${a.user_id}" class="px-4 py-2.5 text-[0.8rem] text-gray-400 border-t border-white/[0.08]">
                <i class="bi bi-alarm mr-1"></i>
                <strong>Extras:</strong> ${formatoHMS(a.extras_segundos)}
                <span class="mx-1">·</span>
                Ent. ${formatoHMS(a.extras_entrada_segundos)}
                <span class="mx-1">·</span>
                Sal. ${formatoHMS(a.extras_salida_segundos)}
            </div>
        `;
        return div;
    }

    function actualizarTarjeta(a) {
        actualizarTextoCard('entrada-card-' + a.user_id, 'bi-box-arrow-in-right', a.hora_entrada);
        actualizarTextoCard('salida-card-' + a.user_id, 'bi-box-arrow-left', a.hora_salida);
        actualizarTextoCard('pausas-card-' + a.user_id, 'bi-cup-hot', formatoHMS(a.pausas_segundos));
        actualizarTextoCard('trabajado-card-' + a.user_id, 'bi-stopwatch', formatoHMS(a.trabajado_segundos));
        actualizarExtrasCard(a.user_id, {
            extras: a.extras_segundos,
            extrasEntrada: a.extras_entrada_segundos,
            extrasSalida: a.extras_salida_segundos,
        });

        const estado = document.getElementById('estado-card-' + a.user_id);
        if (estado) {
            estado.className = 'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ' + a.estado.clase;
            estado.textContent = a.estado.texto;
        }
    }

    function renderTarjetasVacio(contenedor) {
        contenedor.innerHTML = `
            <p id="tarjetas-vacio" class="text-center text-gray-400 py-4 mb-0">
                <i class="bi bi-clock-history text-2xl block mb-2"></i>
                No existen asistencias activas actualmente
            </p>`;
    }

    // ---- Sincronización con el servidor ----
    function sincronizar() {
        fetch(window.RUTAS.tiempos)
            .then(res => res.json())
            .then(data => {
                const usuariosServidor = [];
                const tbody = document.getElementById('tabla-asistencias');
                const vacioTabla = document.getElementById('tabla-vacia');
                if (vacioTabla && data.length > 0) vacioTabla.remove();

                const contenedorCards = document.getElementById('tarjetas-asistencias');
                const vacioCards = document.getElementById('tarjetas-vacio');
                if (vacioCards && data.length > 0) vacioCards.remove();

                const ahora = Date.now();

                data.forEach(a => {
                    usuariosServidor.push(String(a.user_id));

                    const prev = estadoAsistencias[a.user_id];
                    let baseTime = ahora;
                    let pTrabajado = a.trabajado_segundos;
                    let pPausas = a.pausas_segundos;
                    let pExtras = a.extras_segundos;
                    let pExtrasEnt = a.extras_entrada_segundos;
                    let pExtrasSal = a.extras_salida_segundos;

                    // ANTI-JUMPING LOGIC:
                    // Si el estado no ha cambiado, evitamos resetear los valores con los del servidor
                    // para evitar micro-saltos por latencia. Mantenemos la base anterior y dejamos fluir el reloj.
                    if (prev && prev.enPausa === a.en_pausa && prev.turnoTerminado === a.turno_terminado && prev.extrasCreciendo === a.extras_creciendo) {
                        const deltaPrev = (ahora - prev.lastSync) / 1000;
                        const trabajadoEstimado = prev.baseTrabajado + (!a.en_pausa ? deltaPrev : 0);
                        
                        // Si la diferencia entre nuestro cálculo local y el servidor es mínima (< 5s), ignoramos la del server
                        if (Math.abs(trabajadoEstimado - a.trabajado_segundos) < 5) {
                            baseTime = prev.lastSync;
                            pTrabajado = prev.baseTrabajado;
                            pPausas = prev.basePausas;
                            pExtras = prev.baseExtras;
                            pExtrasEnt = prev.baseExtrasEntrada;
                            pExtrasSal = prev.baseExtrasSalida;
                        }
                    }

                    // Guardamos la nueva "Base" en memoria
                    estadoAsistencias[a.user_id] = {
                        baseTrabajado: pTrabajado,
                        basePausas: pPausas,
                        baseExtras: pExtras,
                        baseExtrasEntrada: pExtrasEnt,
                        baseExtrasSalida: pExtrasSal,
                        lastSync: baseTime,
                        enPausa: a.en_pausa,
                        turnoTerminado: a.turno_terminado,
                        extrasCreciendo: a.extras_creciendo,
                        sinRegistro: a.sin_registro
                    };

                    // Calculamos el valor interpolado actual para la primera renderización tras recibir el fetch
                    const deltaCalculado = (ahora - baseTime) / 1000;
                    a.trabajado_segundos = pTrabajado + (!a.en_pausa && !a.turnoTerminado && !a.sin_registro ? deltaCalculado : 0);
                    a.pausas_segundos = pPausas + (a.en_pausa && !a.turnoTerminado && !a.sin_registro ? deltaCalculado : 0);
                    a.extras_segundos = pExtras + (a.extras_creciendo && !a.turnoTerminado && !a.sin_registro ? deltaCalculado : 0);
                    a.extras_salida_segundos = pExtrasSal + (a.extras_creciendo && !a.turnoTerminado && !a.sin_registro ? deltaCalculado : 0);
                    a.extras_entrada_segundos = pExtrasEnt;

                    // Render Tabla
                    let fila = document.querySelector(`tr[data-user="${a.user_id}"]`);
                    if (!fila) {
                        fila = crearFila(a);
                        tbody.appendChild(fila);
                    } else {
                        actualizarFila(a);
                    }

                    // Render Tarjeta
                    let tarjeta = document.querySelector(`[data-user-card="${a.user_id}"]`);
                    if (!tarjeta) {
                        tarjeta = crearTarjeta(a);
                        contenedorCards.appendChild(tarjeta);
                    } else {
                        actualizarTarjeta(a);
                    }
                });

                // Limpieza de usuarios inactivos
                tbody.querySelectorAll('tr[data-user]').forEach(fila => {
                    if (!usuariosServidor.includes(fila.dataset.user)) fila.remove();
                });
                contenedorCards.querySelectorAll('[data-user-card]').forEach(tarjeta => {
                    if (!usuariosServidor.includes(tarjeta.dataset.userCard)) tarjeta.remove();
                });
                Object.keys(estadoAsistencias).forEach(userId => {
                    if (!usuariosServidor.includes(String(userId))) delete estadoAsistencias[userId];
                });

                if (tbody.querySelectorAll('tr[data-user]').length === 0) renderTablaVacia(tbody);
                if (contenedorCards.querySelectorAll('[data-user-card]').length === 0) renderTarjetasVacio(contenedorCards);

                actualizarTarjetasResumen(data);
            })
            .catch(err => console.error('Error sincronizando asistencias:', err));
    }

    // ---- Reloj visual local (entre sincronizaciones) ----
    function tick() {
        const ahora = Date.now();

        Object.keys(estadoAsistencias).forEach(userId => {
            const e = estadoAsistencias[userId];
            if (e.turnoTerminado || e.sinRegistro) return;

            // Calculamos cuánto tiempo real ha pasado desde que guardamos el tiempo base
            const deltaSegundos = (ahora - e.lastSync) / 1000;

            let tTrabajado = e.baseTrabajado;
            let tPausas = e.basePausas;
            let tExtras = e.baseExtras;
            let tExtrasSalida = e.baseExtrasSalida;
            let tExtrasEntrada = e.baseExtrasEntrada;

            if (e.enPausa) {
                tPausas += deltaSegundos;
            } else {
                tTrabajado += deltaSegundos;
            }

            if (e.extrasCreciendo) {
                tExtras += deltaSegundos;
                tExtrasSalida += deltaSegundos;
            }

            const dataExtras = {
                extras: tExtras,
                extrasEntrada: tExtrasEntrada,
                extrasSalida: tExtrasSalida
            };

            // Actualizamos la UI sin alterar el estado guardado, solo calculamos la diferencia
            actualizarTexto('pausas-' + userId, 'bi-cup-hot', formatoHMS(tPausas));
            actualizarTexto('trabajado-' + userId, 'bi-stopwatch', formatoHMS(tTrabajado));
            actualizarExtras(userId, dataExtras);

            actualizarTextoCard('pausas-card-' + userId, 'bi-cup-hot', formatoHMS(tPausas));
            actualizarTextoCard('trabajado-card-' + userId, 'bi-stopwatch', formatoHMS(tTrabajado));
            actualizarExtrasCard(userId, dataExtras);
        });
    }

    // ---- Control de polling ----
    function iniciarPolling() {
        clearInterval(intervaloSincronizacion);
        intervaloSincronizacion = setInterval(sincronizar, POLLING_MS);
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(intervaloSincronizacion);
        } else {
            sincronizar();
            iniciarPolling();
        }
    });

    // ---- Arranque ----
    sincronizar();
    iniciarPolling();
    setInterval(tick, TICK_MS); // tick funciona a 1000ms

})();