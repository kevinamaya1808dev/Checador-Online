// ==========================================================
// dashboard-becario.js
// Lógica del Dashboard del Becario
// ==========================================================


// ==========================================================
// CONFIGURACIÓN INICIAL
// (window.checadorConfig se sobreescribe desde el blade con
//  los datos reales: estado, horaEntrada, pausaInicio, etc.)
// ==========================================================

window.checadorConfig = window.checadorConfig || {};


// ==========================================================
// FUNCIONES DE MODALES
// ==========================================================

window.openModal = function (modalId) {

    const modal = document.getElementById(modalId);

    if (!modal) return;

    const dialog = modal.querySelector('.modal-dialog');

    modal.classList.remove('opacity-0', 'pointer-events-none');

    setTimeout(() => {

        if (dialog) {
            dialog.classList.remove('scale-95');
            dialog.classList.add('scale-100');
        }

    }, 10);

};


window.closeModal = function (modalId) {

    // Acepta tanto un id (string) como el propio elemento del modal
    const modal = typeof modalId === 'string'
        ? document.getElementById(modalId)
        : modalId;

    if (!modal) return;

    const dialog = modal.querySelector('.modal-dialog');

    if (dialog) {
        dialog.classList.remove('scale-100');
        dialog.classList.add('scale-95');
    }

    modal.classList.add('opacity-0');

    setTimeout(() => {
        modal.classList.add('pointer-events-none');
    }, 300);

};


// ==========================================================
// MENÚ DE PAUSA
// ==========================================================

window.togglePausaMenu = function () {

    const menu = document.getElementById('pausaMenu');
    const chevron = document.getElementById('pausaChevron');

    if (!menu) return;

    const cerrado = menu.classList.contains('max-h-0');

    if (cerrado) {
        menu.classList.remove('max-h-0', 'opacity-0');
        menu.classList.add('max-h-[320px]', 'opacity-100');
        if (chevron) chevron.classList.add('rotate-180');
    } else {
        menu.classList.remove('max-h-[320px]', 'opacity-100');
        menu.classList.add('max-h-0', 'opacity-0');
        if (chevron) chevron.classList.remove('rotate-180');
    }

};


// ==========================================================
// CARGA PRINCIPAL (todo el DOM ya está disponible aquí)
// ==========================================================

document.addEventListener('DOMContentLoaded', () => {

    // ---------------------------------------
    // Animación de entrada
    // ---------------------------------------

    document.querySelectorAll('.entrada').forEach((elemento, indice) => {
        if (!elemento.style.animationDelay) {
            elemento.style.animationDelay = `${indice * 0.08}s`;
        }
    });


    // ---------------------------------------
    // Toasts
    // ---------------------------------------

    document.querySelectorAll('[data-toast]').forEach((toast) => {

        const cerrarToast = () => {
            toast.style.transition = 'opacity .3s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        };

        const botonCerrar = toast.querySelector('[data-toast-close]');
        if (botonCerrar) botonCerrar.addEventListener('click', cerrarToast);

        setTimeout(cerrarToast, 3000);

    });


    // ---------------------------------------
    // Referencias del reloj
    // ---------------------------------------

    const hora = document.getElementById('reloj-hora');
    const minuto = document.getElementById('reloj-min');
    const segundo = document.getElementById('reloj-seg');

    const relojDigital = document.getElementById('reloj-digital');
    const relojFecha = document.getElementById('reloj-fecha');


    // ---------------------------------------
    // Actualizar reloj (analógico + digital 12h con AM/PM)
    // ---------------------------------------

    function actualizarReloj() {

        const ahora = new Date();

        const h24 = ahora.getHours();
        const m = ahora.getMinutes();
        const s = ahora.getSeconds();

        // Manecillas: solo rotate(), sin translateX,
        // porque el centrado ya lo hace el CSS (left-1/2 + margin negativo)
        if (hora) hora.style.transform = `rotate(${(h24 % 12) * 30 + m * 0.5}deg)`;
        if (minuto) minuto.style.transform = `rotate(${m * 6}deg)`;
        if (segundo) segundo.style.transform = `rotate(${s * 6}deg)`;

        let h12 = h24 % 12;
        h12 = h12 === 0 ? 12 : h12;
        const meridiano = h24 >= 12 ? 'PM' : 'AM';

        if (relojDigital) {
            relojDigital.innerHTML =
                `${String(h12).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')} ` +
                `<span class="text-lg sm:text-xl text-blue-500">${meridiano}</span>`;
        }

        if (relojFecha) {
            relojFecha.textContent = ahora.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

    }


    // ---------------------------------------
    // Tiempos de trabajo / pausa
    // (calculados con timestamps reales, no con
    //  contadores ingenuos que se pierden al recargar)
    // ---------------------------------------

    const elTrabajado = document.getElementById('tiempoTrabajado');
    const elPausa = document.getElementById('tiempoPausa');

    function formatearDuracion(totalSegundos) {
        totalSegundos = Math.max(0, Math.floor(totalSegundos) || 0);
        const h = String(Math.floor(totalSegundos / 3600)).padStart(2, '0');
        const m = String(Math.floor((totalSegundos % 3600) / 60)).padStart(2, '0');
        const s = String(Math.floor(totalSegundos % 60)).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    function calcularTiempos() {

        const cfg = window.checadorConfig || {};
        const ahora = Date.now();

        let segundosTrabajados = 0;
        let segundosPausados = Number(cfg.segundosPausaAcumulados) || 0;

        const inicio = cfg.horaEntrada ? new Date(cfg.horaEntrada).getTime() : null;

        if (inicio && !isNaN(inicio)) {

            if (cfg.estado === 'pausado' && cfg.pausaInicio) {

                const pausaInicio = new Date(cfg.pausaInicio).getTime();

                if (!isNaN(pausaInicio)) {
                    segundosTrabajados = Math.floor((pausaInicio - inicio) / 1000) - segundosPausados;
                    segundosPausados += Math.floor((ahora - pausaInicio) / 1000);
                }

            } else if (cfg.estado === 'terminado') {

                const finRaw = cfg.horaSalida ? new Date(cfg.horaSalida).getTime() : ahora;
                const fin = isNaN(finRaw) ? ahora : finRaw;
                segundosTrabajados = Math.floor((fin - inicio) / 1000) - segundosPausados;

            } else if (cfg.estado === 'trabajando') {

                segundosTrabajados = Math.floor((ahora - inicio) / 1000) - segundosPausados;

            }

        }

        segundosTrabajados = Math.max(0, segundosTrabajados || 0);
        segundosPausados = Math.max(0, segundosPausados || 0);

        if (elTrabajado) elTrabajado.textContent = formatearDuracion(segundosTrabajados);
        if (elPausa) elPausa.textContent = formatearDuracion(segundosPausados);

    }

    actualizarReloj();
    calcularTiempos();

    setInterval(() => {
        actualizarReloj();
        calcularTiempos();
    }, 1000);


    // ==========================================
    // MODALES
    // ==========================================

    // Cerrar con los botones internos (la "X" y "Cancelar",
    // ambos usan la clase .btn-close-modal en tu HTML real)
    document.querySelectorAll('.btn-close-modal').forEach((btn) => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('[role="dialog"]');
            if (modal) window.closeModal(modal);
        });
    });

    // Cerrar al hacer clic fuera del cuadro de diálogo
    document.querySelectorAll('[role="dialog"]').forEach((modal) => {
        modal.addEventListener('mousedown', (e) => {
            if (e.target === modal) window.closeModal(modal);
        });
    });

    // Cerrar con tecla ESC
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[role="dialog"]').forEach((modal) => {
            if (!modal.classList.contains('pointer-events-none')) {
                window.closeModal(modal);
            }
        });
    });

    // Confirmar "Finalizar pausa" -> envía el formulario real
    const btnConfirmarPausa = document.getElementById('confirmarFinalizarPausa');
    if (btnConfirmarPausa) {
        btnConfirmarPausa.addEventListener('click', () => {
            const formulario = document.getElementById('formFinalizarPausa');
            if (formulario) formulario.submit();
        });
    }

    // Confirmar "Registrar salida" -> envía el formulario real
    const btnConfirmarSalida = document.getElementById('confirmarSalida');
    if (btnConfirmarSalida) {
        btnConfirmarSalida.addEventListener('click', () => {
            const formulario = document.getElementById('formSalida');
            if (formulario) formulario.submit();
        });
    }

});