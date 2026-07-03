<div class="modal fade" id="modalRegistrarBecario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Registrar Nuevo Becario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.becarios.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="name" id="nombre_becario" class="form-control bg-dark text-white border-secondary" 
                               onkeyup="generarEmailAuto()" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Corporativo</label>
                        <input type="email" name="email" id="email_corporativo" class="form-control bg-dark text-white border-secondary" 
                               readonly required>
                        <small class="text-secondary">El correo se genera automáticamente.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña Temporal</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <input type="hidden" name="role" value="becario">
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Becario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generarEmailAuto() {
    let nombre = document.getElementById('nombre_becario').value;
    // Convierte a minúsculas, quita espacios y agrega el dominio
    let email = nombre.toLowerCase().replace(/\s+/g, '.');
    document.getElementById('email_corporativo').value = email + "@empresa.com";
}
</script>