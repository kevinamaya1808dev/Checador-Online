<!-- Modal Confirmar Finalizar Descanso -->
<div class="modal fade" id="modalFinalizarPausa" tabindex="-1" aria-labelledby="modalFinalizarPausaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white shadow-lg" style="border:1px solid rgba(255,255,255,.08);border-top:3px solid #0d6efd;border-radius:.75rem;">

            <div class="modal-header border-0 pb-2">

                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;border-radius:.5rem;background:rgba(255,193,7,.12);">
                        <i class="bi bi-cup-hot-fill" style="color:#ffc107;font-size:1.15rem;"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small mb-1" style="letter-spacing:.08em;color:#8b93a1;font-size:.7rem;">
                            Confirmación
                        </p>
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

            <div class="modal-body pt-0 pb-3">
                <p class="mb-0 text-white-50">
                    ¿Estás seguro de que deseas finalizar tu descanso y
                    regresar a tus actividades?
                </p>
            </div>

            <div class="modal-footer border-0 d-grid gap-2 d-sm-flex justify-content-sm-end pt-0">

                <button
                    type="button"
                    class="btn btn-outline-light px-4"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button
                    type="button"
                    class="btn btn-primary px-4"
                    id="confirmarFinalizarPausa">
                    <i class="bi bi-check-lg me-1"></i>
                    Sí, finalizar descanso
                </button>

            </div>

        </div>
    </div>
</div>

<style>
    #modalFinalizarPausa.modal.fade .modal-dialog {
        transition: transform .3s cubic-bezier(.16,1,.3,1), opacity .3s ease;
        transform: translateY(-8px) scale(.97);
    }
    #modalFinalizarPausa.modal.show .modal-dialog {
        transform: translateY(0) scale(1);
    }
    #modalFinalizarPausa .btn-primary {
        transition: transform .15s ease, box-shadow .15s ease;
    }
    #modalFinalizarPausa .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13,110,253,.35);
    }
</style>