<div class="modal fade" id="modalRegistrarBecario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark border border-secondary text-white shadow-lg">
            <div class="modal-header border-0" style="background: rgba(13, 110, 253, 0.15);">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="bi bi-person-plus-fill me-2"></i>Registrar Nuevo Becario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.becarios.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="text-secondary fw-bold text-uppercase small mb-3">
                            <i class="bi bi-person-badge me-1"></i> Información del Becario
                        </label>
                        <div class="mb-3">
                            <label class="form-label text-light">Nombre Completo</label>
                            <input type="text" name="name" id="nombre_becario"
                                   class="form-control bg-dark text-white border-secondary"
                                   onkeyup="generarEmailAuto()" placeholder="Ej. Juan Pérez" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light">Usuario de Acceso (Correo)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary text-white border-secondary">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="text" id="email_prefix" class="form-control bg-dark text-white border-secondary"
                                       readonly placeholder="usuario">
                                <span class="input-group-text bg-secondary text-white border-secondary">@ollintem.com.mx</span>
                            </div>
                            <input type="hidden" name="email" id="email_corporativo">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-secondary fw-bold text-uppercase small mb-3">
                            <i class="bi bi-shield-lock me-1"></i> Configuración de Seguridad
                        </label>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Contraseña</label>
                                <input type="password" name="password" class="form-control bg-dark text-white border-secondary"
                                       placeholder="Mínimo 8 caracteres" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-secondary"
                                       placeholder="Repetir contraseña" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="role" value="becario">
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Guardar Becario</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function generarEmailAuto() {
    let nombre = document.getElementById('nombre_becario').value;
    let emailBase = nombre.toLowerCase().replace(/\s+/g, '.');
    document.getElementById('email_prefix').value = emailBase;
    document.getElementById('email_corporativo').value = emailBase + "@ollintem.com.mx";
}
</script> 