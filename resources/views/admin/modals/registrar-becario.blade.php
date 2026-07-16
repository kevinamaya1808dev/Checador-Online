<div class="d-flex justify-content-end mb-3">
    <button type="button" onclick="openModal('modalRegistrarBecario')" 
            class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-blue-400 bg-blue-500/10 border border-blue-500/30 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm focus:outline-none">
        <i class="bi bi-person-plus text-lg"></i>
        <span>Nuevo Becario</span>
    </button>
</div>

<div id="modalRegistrarBecario" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 ease-out" 
     role="dialog" aria-modal="true">
    
    <div class="modal-dialog relative w-full max-w-2xl mx-4 bg-[#1b1e24] text-white border border-white/10 rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-90"></div>

        <div class="flex items-center justify-between px-6 pt-6 pb-4 bg-blue-500/5">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center shrink-0 w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xl shadow-[0_0_15px_rgba(59,130,246,0.15)]">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <h5 class="text-lg font-bold text-white m-0">Registrar Nuevo Becario</h5>
                </div>
            </div>

            <button type="button" class="btn-close-modal text-gray-400 hover:text-white transition-colors" aria-label="Cerrar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="h-px bg-white/10"></div>

        <form action="{{ route('admin.becarios.store') }}" method="POST">
            @csrf
            <div class="px-6 py-5">
                
                <div class="mb-6">
                    <label class="flex items-center gap-2 text-xs font-bold tracking-widest text-gray-400 uppercase mb-4">
                        <i class="bi bi-person-badge text-gray-500"></i> Información del Becario
                    </label>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Nombre Completo</label>
                            <input type="text" name="name" id="nombre_becario"
                                   class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   onkeyup="generarEmailAuto()" placeholder="Ej. Juan Pérez" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Usuario de Acceso (Correo)</label>
                            <div class="flex rounded-lg shadow-sm">
                                <span class="inline-flex items-center px-4 py-2 bg-white/10 border border-r-0 border-white/10 rounded-l-lg text-gray-400">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="text" id="email_prefix" readonly placeholder="usuario"
                                       class="w-full px-4 py-2 bg-white/5 border-y border-white/10 text-gray-400 focus:outline-none cursor-not-allowed placeholder-gray-600">
                                <span class="inline-flex items-center px-4 py-2 bg-white/10 border border-l-0 border-white/10 rounded-r-lg text-gray-400 text-sm">
                                    @ollintem.com.mx
                                </span>
                            </div>
                            <input type="hidden" name="email" id="email_corporativo">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-xs font-bold tracking-widest text-gray-400 uppercase mb-4">
                        <i class="bi bi-shield-lock text-gray-500"></i> Configuración de Seguridad
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                            <input type="password" name="password" placeholder="Mínimo 8 caracteres" required
                                   class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" placeholder="Repetir contraseña" required
                                   class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="role" value="becario">
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 bg-white/5 border-t border-white/10">
                <button type="button" class="btn-close-modal w-full sm:w-auto px-5 py-2 text-sm font-medium text-white border border-white/20 rounded-lg hover:bg-white/10 transition-colors focus:outline-none">
                    Cancelar
                </button>
                <button type="submit" class="w-full sm:w-auto px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:bg-blue-500 hover:-translate-y-0.5 transition-all focus:outline-none">
                    Guardar Becario
                </button>
            </div>
        </form>
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