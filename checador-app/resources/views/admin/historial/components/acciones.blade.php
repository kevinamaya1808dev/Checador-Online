<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="row align-items-center">

            {{-- Botón regresar --}}
            <div class="col-lg-3 mb-3 mb-lg-0">

                <a
                    href="{{ route('admin.historial') }}"
                    class="btn btn-outline-secondary w-100">

                    <i class="bi bi-arrow-left"></i>

                    Regresar al historial

                </a>

            </div>

            {{-- Periodo --}}
            <div class="col-lg-5">

                <form class="row g-2">

                    <div class="col">

                        <input
                            type="date"
                            class="form-control">

                    </div>

                    <div class="col">

                        <input
                            type="date"
                            class="form-control">

                    </div>

                </form>

            </div>

            {{-- Acciones --}}
            <div class="col-lg-4">

                <div class="d-grid gap-2 d-md-flex justify-content-end">

                    <button
                        class="btn btn-success">

                        <i class="bi bi-file-earmark-excel"></i>

                        Excel

                    </button>

                    <button
                        class="btn btn-danger">

                        <i class="bi bi-file-earmark-pdf"></i>

                        PDF

                    </button>

                    <button
                        onclick="window.print()"
                        class="btn btn-primary">

                        <i class="bi bi-printer"></i>

                    </button>

                </div>

            </div>

        </div>

    </div>

</div> 