<!-- Modal Confirmar Salida -->
<div class="modal fade" id="modalSalida" tabindex="-1" aria-labelledby="modalSalidaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white shadow-lg" style="border:1px solid rgba(255,255,255,.08);border-top:3px solid #dc3545;border-radius:.75rem;">

            <div class="modal-header border-0 pb-2">

                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;border-radius:.5rem;background:rgba(220,53,69,.12);">
                        <i class="bi bi-box-arrow-left" style="color:#dc3545;font-size:1.15rem;"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small mb-1" style="letter-spacing:.08em;color:#8b93a1;font-size:.7rem;">
                            Confirmación
                        </p>
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

            <div class="modal-body pt-0 pb-3">
                <p class="mb-0 text-white-50">
                    ¿Deseas finalizar tu turno? Esta acción registrará tu salida.
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
                    class="btn btn-danger px-4"
                    id="confirmarSalida">
                    <i class="bi bi-check-lg me-1"></i>
                    Sí, finalizar turno
                </button>

            </div>

        </div>
    </div>
</div>

<style>
    #modalSalida.modal.fade .modal-dialog {
        transition: transform .3s cubic-bezier(.16,1,.3,1), opacity .3s ease;
        transform: translateY(-8px) scale(.97);
    }
    #modalSalida.modal.show .modal-dialog {
        transform: translateY(0) scale(1);
    }
    #modalSalida .btn-danger {
        transition: transform .15s ease, box-shadow .15s ease;
    }
    #modalSalida .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220,53,69,.35);
    }
</style>