<!-- Modal Confirmar Finalizar Descanso -->
<div class="modal fade" id="modalFinalizarPausa" tabindex="-1" aria-labelledby="modalFinalizarPausaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content--dual" id="modalFinalizarPausaContent">

            <div class="modal-header border-0 pb-2">

                <div class="d-flex align-items-center gap-3">
                    <div class="icon-badge">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <div>
                        <p class="eyebrow mb-1">Confirmación</p>
                        <h5 class="modal-title fw-bold mb-0" id="modalFinalizarPausaLabel">
                            Confirmar término de descanso
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
                    ¿Estás seguro de que deseas finalizar tu descanso y
                    regresar a tus actividades?
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
                    class="btn btn-primary px-4 btn-refined btn-refined--accent"
                    id="confirmarFinalizarPausa">
                    <i class="bi bi-check-lg me-1"></i>
                    Sí, finalizar descanso
                </button>

            </div>

        </div>
    </div>
</div>

<style>
    /* ============================================================
       Modal de confirmación — variante "finalizar pausa"
       Acento dual: ámbar (contexto: descanso) + azul (acción: volver)
       ============================================================ */

    #modalFinalizarPausa {
        --accent: #0d6efd;          /* acción: confirmar / retomar actividad */
        --accent-glow: rgba(13,110,253,.28);
        --context: #ffc107;         /* contexto: descanso / pausa */
        --context-soft: rgba(255,193,7,.14);
    }

    #modalFinalizarPausa .modal-content--dual {
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

    /* Barra superior: degradado azul (acción) que se desvanece en los bordes,
       con un matiz ámbar apenas perceptible en el centro — el eco del contexto */
    #modalFinalizarPausa .modal-content--dual::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg,
            transparent,
            var(--accent) 22%,
            color-mix(in srgb, var(--accent) 55%, var(--context)) 50%,
            var(--accent) 78%,
            transparent
        );
        opacity: .9;
    }

    /* Halo del ícono en tono ámbar — conserva el color de contexto original */
    #modalFinalizarPausa .icon-badge {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: .6rem;
        background: var(--context-soft);
        box-shadow: 0 0 0 5px rgba(255,193,7,.05);
    }

    #modalFinalizarPausa .icon-badge i {
        color: var(--context);
        font-size: 1.2rem;
        filter: drop-shadow(0 1px 3px rgba(255,193,7,.35));
    }

    #modalFinalizarPausa .eyebrow {
        text-transform: uppercase;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .12em;
        color: #8b93a1;
        margin: 0 0 .2rem 0;
    }

    #modalFinalizarPausa .modal-title {
        font-size: 1.05rem;
        letter-spacing: -.01em;
    }

    #modalFinalizarPausa .modal-hairline {
        height: 1px;
        margin: 0 1.5rem;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.09), transparent);
    }

    #modalFinalizarPausa .modal-body p {
        line-height: 1.6;
        font-size: .93rem;
    }

    /* Botones: la acción (azul) es la que recibe el peso visual;
       cancelar se mantiene discreto, sin competir con el contexto ámbar */
    #modalFinalizarPausa .btn-refined {
        font-weight: 500;
        border-radius: .55rem;
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease, background-color .15s ease;
    }

    #modalFinalizarPausa .btn-outline-light.btn-refined {
        border-color: rgba(255,255,255,.18);
    }

    #modalFinalizarPausa .btn-outline-light.btn-refined:hover {
        background-color: rgba(255,255,255,.06);
        border-color: rgba(255,255,255,.28);
    }

    #modalFinalizarPausa .btn-refined--accent {
        background: linear-gradient(180deg, #3d8bfd, var(--accent));
        border: none;
        box-shadow: 0 2px 10px rgba(13,110,253,.35);
    }

    #modalFinalizarPausa .btn-refined--accent:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(13,110,253,.45);
        filter: brightness(1.05);
    }

    #modalFinalizarPausa .btn-refined:active {
        transform: translateY(0) scale(.97);
    }

    #modalFinalizarPausa .btn-refined:focus-visible {
        outline: 2px solid rgba(13,110,253,.6);
        outline-offset: 2px;
    }

    #modalFinalizarPausa.modal.fade .modal-dialog {
        transition: transform .32s cubic-bezier(.16,1,.3,1), opacity .28s ease;
        transform: translateY(-10px) scale(.96);
    }
    #modalFinalizarPausa.modal.show .modal-dialog {
        transform: translateY(0) scale(1);
    }

    @media (prefers-reduced-motion: reduce) {
        #modalFinalizarPausa.modal.fade .modal-dialog,
        #modalFinalizarPausa .btn-refined,
        #modalFinalizarPausa .btn-refined--accent:hover {
            transition: none;
            transform: none;
        }
    }
</style>