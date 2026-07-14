<!-- Modal Confirmar Salida -->
<div class="modal fade" id="modalSalida" tabindex="-1" aria-labelledby="modalSalidaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content--accent" id="modalSalidaContent">

            <div class="modal-header border-0 pb-2">

                <div class="d-flex align-items-center gap-3">
                    <div class="icon-badge">
                        <i class="bi bi-box-arrow-left"></i>
                    </div>
                    <div>
                        <p class="eyebrow mb-1">Confirmación</p>
                        <h5 class="modal-title fw-bold mb-0" id="modalSalidaLabel">
                            Confirmar finalizar turno
                        </h5>
                    </div>
                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-hairline"></div>

            <div class="modal-body pt-3 pb-3">
                <p class="mb-0 text-white-50">
                    ¿Deseas finalizar tu turno? Esta acción registrará tu salida.
                </p>
            </div>

            <div class="modal-hairline"></div>

            <div class="modal-footer border-0 d-grid gap-2 d-sm-flex justify-content-sm-end pt-3">

                <button
                    type="button"
                    class="btn btn-outline-light px-4 btn-refined"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button
                    type="button"
                    class="btn btn-danger px-4 btn-refined btn-refined--accent"
                    id="confirmarSalida">
                    <i class="bi bi-check-lg me-1"></i>
                    Sí, finalizar turno
                </button>

            </div>

        </div>
    </div>
</div>

<style>
    /* ============================================================
       Modal de confirmación — variante "salida" (accent: rojo)
       Para reutilizar en Entrada/Pausa: duplica este bloque, cambia
       el id (#modalSalida → #modalEntrada), el valor de --accent
       y el ícono de .icon-badge.
       ============================================================ */

    #modalSalida {
        --accent: #dc3545;
        --accent-soft: rgba(220, 53, 69, 0.14);
        --accent-glow: rgba(220, 53, 69, 0.28);
    }

    #modalSalida .modal-content--accent {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(180deg, rgba(255,255,255,.035), rgba(255,255,255,0) 40%),
            #1b1e24;
        color: #fff;
        border: 1px solid rgba(255,255,255,.07);
        border-radius: .85rem;
        box-shadow:
            0 24px 60px -18px rgba(0,0,0,.65),
            0 10px 28px -10px var(--accent-glow),
            inset 0 1px 0 rgba(255,255,255,.04);
    }

    /* Línea de acento superior, difuminada en los extremos en vez de un borde recto */
    #modalSalida .modal-content--accent::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--accent) 25%, var(--accent) 75%, transparent);
        opacity: .9;
    }

    /* Halo suave detrás del ícono, para dar profundidad sin recargar */
    #modalSalida .icon-badge {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: .6rem;
        background: var(--accent-soft);
        box-shadow: 0 0 0 5px rgba(220,53,69,.05);
    }

    #modalSalida .icon-badge i {
        color: var(--accent);
        font-size: 1.2rem;
        filter: drop-shadow(0 1px 3px rgba(220,53,69,.35));
    }

    #modalSalida .eyebrow {
        text-transform: uppercase;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .12em;
        color: #8b93a1;
        margin: 0 0 .2rem 0;
    }

    #modalSalida .modal-title {
        font-size: 1.05rem;
        letter-spacing: -.01em;
    }

    /* Hairlines con desvanecido lateral en vez de <hr> o border-top duro */
    #modalSalida .modal-hairline {
        height: 1px;
        margin: 0 1.5rem;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.09), transparent);
    }

    #modalSalida .modal-body p {
        line-height: 1.6;
        font-size: .93rem;
    }

    /* Botones: interacción táctil deliberada — press-down + lift on hover */
    #modalSalida .btn-refined {
        font-weight: 500;
        border-radius: .55rem;
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease, background-color .15s ease;
    }

    #modalSalida .btn-outline-light.btn-refined {
        border-color: rgba(255,255,255,.18);
    }

    #modalSalida .btn-outline-light.btn-refined:hover {
        background-color: rgba(255,255,255,.06);
        border-color: rgba(255,255,255,.28);
    }

    #modalSalida .btn-refined--accent {
        background: linear-gradient(180deg, #e24a5a, var(--accent));
        border: none;
        box-shadow: 0 2px 10px rgba(220,53,69,.35);
    }

    #modalSalida .btn-refined--accent:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(220,53,69,.45);
        filter: brightness(1.05);
    }

    #modalSalida .btn-refined:active {
        transform: translateY(0) scale(.97);
    }

    /* Foco visible para accesibilidad de teclado */
    #modalSalida .btn-refined:focus-visible {
        outline: 2px solid rgba(220,53,69,.6);
        outline-offset: 2px;
    }

    /* Entrada del modal: mismo timing que ya tenías, con leve refinamiento */
    #modalSalida.modal.fade .modal-dialog {
        transition: transform .32s cubic-bezier(.16,1,.3,1), opacity .28s ease;
        transform: translateY(-10px) scale(.96);
    }
    #modalSalida.modal.show .modal-dialog {
        transform: translateY(0) scale(1);
    }

    /* Respeta la preferencia de movimiento reducido */
    @media (prefers-reduced-motion: reduce) {
        #modalSalida.modal.fade .modal-dialog,
        #modalSalida .btn-refined,
        #modalSalida .btn-refined--accent:hover {
            transition: none;
            transform: none;
        }
    }
</style>