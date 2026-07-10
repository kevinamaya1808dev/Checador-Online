<div
    class="modal fade"
    id="detalleJornada"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content bg-dark border-secondary text-light">

            <div class="modal-header border-secondary">

                <h5 class="modal-title">
                    <i class="bi bi-clock-history text-primary"></i>
                    Detalle de la jornada
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

    <div class="row mb-4">

        <div class="col-md-8">

            <h4 id="modalBecario" class="fw-bold mb-1"></h4>

            <span id="modalEmail" class="text-secondary"></span>

        </div>

        <div class="col-md-4 text-md-end">

            <small class="text-secondary">Fecha</small>

            <h5 id="modalFecha"></h5>

        </div>

    </div>

    <div class="row text-center g-3">

        <div class="col-md-4">

            <div class="card bg-secondary bg-opacity-10 border-secondary">

                <div class="card-body">

                    <small class="text-secondary">Entrada</small>

                    <h5 id="modalEntrada"></h5>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card bg-secondary bg-opacity-10 border-secondary">

                <div class="card-body">

                    <small class="text-secondary">Salida</small>

                    <h5 id="modalSalida"></h5>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card bg-secondary bg-opacity-10 border-secondary">

                <div class="card-body">

                    <small class="text-secondary">Horas extra</small>

                    <h5 id="modalExtra" class="text-success"></h5>

                </div>

            </div>

        </div>

    </div>

    <hr>

    <div class="row text-center">

        <div class="col-md-6">

            <small class="text-secondary">

                Tiempo trabajado

            </small>

            <h4 id="modalTrabajo" class="text-info"></h4>

        </div>

        <div class="col-md-6">

            <small class="text-secondary">

                Tiempo de pausas

            </small>

            <h4 id="modalPausas" class="text-warning"></h4>

        </div>

    </div>

    <hr>

<h5 class="mb-3">

    <i class="bi bi-cup-hot me-2 text-warning"></i>

    Historial de pausas

</h5>

<div class="table-responsive">

    <table class="table table-dark table-hover align-middle">

        <thead>

            <tr>

                <th>#</th>

                <th>Motivo</th>

                <th>Inicio</th>

                <th>Fin</th>

                <th>Duración</th>

            </tr>

        </thead>

        <tbody id="tablaPausas">

            <tr>

                <td colspan="5" class="text-center text-secondary">

                    Sin información.

                </td>

            </tr>

        </tbody>

    </table>

</div>

</div>

        </div>

    </div>
</div>

<script>

document.addEventListener("DOMContentLoaded", () => {

    const modal = document.getElementById("detalleJornada");

    modal.addEventListener("show.bs.modal", async function (event) {

    const button = event.relatedTarget;

    const id = button.dataset.id;

    const response = await fetch(`/admin/historial/${id}`);

    const asistencia = await response.json();

    document.getElementById("modalBecario").textContent = asistencia.user.name;

    document.getElementById("modalEmail").textContent = asistencia.user.email;

    document.getElementById("modalFecha").textContent =
        new Date(asistencia.fecha).toLocaleDateString();

    document.getElementById("modalEntrada").textContent =
        asistencia.hora_entrada ?? "--";

    document.getElementById("modalSalida").textContent =
        asistencia.hora_salida ?? "--";

    document.getElementById("modalTrabajo").textContent =
        button.dataset.trabajo;

    document.getElementById("modalPausas").textContent =
        button.dataset.pausas;

    document.getElementById("modalExtra").textContent =
        button.dataset.extra;

        const tablaPausas = document.getElementById("tablaPausas");

tablaPausas.innerHTML = "";

if (asistencia.pausas.length === 0) {

    tablaPausas.innerHTML = `
        <tr>
            <td colspan="5" class="text-center text-secondary">
                No hubo pausas registradas.
            </td>
        </tr>
    `;

} else {

    asistencia.pausas.forEach((pausa, index) => {

        const inicio = pausa.inicio_pausa
            ? new Date("1970-01-01T" + pausa.inicio_pausa)
                .toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                })
            : "--";

        const fin = pausa.fin_pausa
            ? new Date("1970-01-01T" + pausa.fin_pausa)
                .toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                })
            : "--";

        tablaPausas.innerHTML += `
            <tr>

                <td>${index + 1}</td>

                <td>${pausa.motivo}</td>

                <td>${inicio}</td>

                <td>${fin}</td>

                <td>${pausa.duracion_formato}</td>

            </tr>
        `;

    });

}

});

});

</script>