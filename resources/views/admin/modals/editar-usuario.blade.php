<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark border border-secondary shadow-lg">
            <div class="modal-header border-0" style="background: rgba(13, 110, 253, 0.15);">
                <h5 class="modal-title fw-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i>Editar Empleado: <span id="nombre_actual_display"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditar" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="text-secondary fw-bold text-uppercase small mb-3">
                            <i class="bi bi-key-fill me-1"></i> Datos de acceso
                        </label>
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" id="edit_name"
                            class="form-control border-secondary"
                            oninput="actualizarCorreoAuto()" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label ">Usuario de Acceso</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary  border-secondary"><i class="bi bi-envelope"></i></span>
                                <input type="text" id="edit_email_prefix" class="form-control border-secondary" readonly>
                                <span class="input-group-text bg-secondary border-secondary">@ollintem.com.mx</span>
                            </div>
                            <small class="text-secondary">Modifica el usuario solo si es estrictamente necesario.</small>
                            <input type="hidden" name="email" id="full_email_hidden">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label ">Cargo (Rol)</label>
                        <select name="role" id="edit_role" class="form-select  border-secondary">
                            <option value="becario">Becario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nueva Contraseña <small class="text-secondary">(Opcional)</small></label>
                            <input type="password" name="password" class="form-control border-secondary" placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label ">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control border-secondary">
                        </div>
                    </div>
                    <small class="text-secondary">Déjalo en blanco si no deseas cambiarla.</small>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function prepararEdicion(id, name, role, email) {
    document.getElementById('formEditar').action = '/admin/user/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_role').value = role;
    // Al abrir el modal, establecemos el correo inicial
    let prefix = email.split('@')[0];
    document.getElementById('edit_email_prefix').value = prefix;
    document.getElementById('full_email_hidden').value = email;
}
function actualizarCorreoAuto() {
    let nuevoNombre = document.getElementById('edit_name').value;
    // Generar el formato nombre.apellido (reemplaza espacios por puntos)
    let nuevoPrefix = nuevoNombre.toLowerCase().replace(/\s+/g, '.');
    // Actualizar vista y campo oculto
    document.getElementById('edit_email_prefix').value = nuevoPrefix;
    document.getElementById('full_email_hidden').value = nuevoPrefix + "@ollintem.com.mx";
}
</script>