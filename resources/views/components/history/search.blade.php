@props([
    'meses'
])

<form method="GET" class="mb-4">

    <div class="card">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                {{-- Buscar --}}
                <div class="col-lg-4">

                    <label class="form-label text-secondary">
                        Buscar becario
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Nombre o correo..."
                        value="{{ request('search') }}"
                    >

                </div>

                {{-- Semana --}}
                <div class="col-lg-2">

                    <label class="form-label text-secondary">
                        Semana
                    </label>

                    <select
                        name="semana"
                        class="form-select"
                    >

                        <option value="">Todas</option>

                        <option value="1" @selected(request('semana')==1)>1 - 7</option>

                        <option value="2" @selected(request('semana')==2)>8 - 14</option>

                        <option value="3" @selected(request('semana')==3)>15 - 21</option>

                        <option value="4" @selected(request('semana')==4)>22 - 28</option>

                        <option value="5" @selected(request('semana')==5)>29 - Fin</option>

                    </select>

                </div>

                {{-- Mes --}}
                <div class="col-lg-2">

                    <label class="form-label text-secondary">
                        Mes
                    </label>

                    <select
                        name="mes"
                        class="form-select"
                    >

                        <option value="">Todos</option>

                        @foreach($meses as $mes)

                            <option
                                value="{{ $mes->numero_mes }}"
                                @selected(request('mes')==$mes->numero_mes)
                            >

                                {{ \Carbon\Carbon::create()->month($mes->numero_mes)->translatedFormat('F') }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Orden --}}
                <div class="col-lg-2">

                    <label class="form-label text-secondary">
                        Orden
                    </label>

                    <select
                        name="order"
                        class="form-select"
                    >

                        <option value="az" @selected(request('order')=='az')>
                            A-Z
                        </option>

                        <option value="za" @selected(request('order')=='za')>
                            Z-A
                        </option>

                        <option value="recent" @selected(request('order')=='recent')>
                            Más reciente
                        </option>

                        <option value="oldest" @selected(request('order')=='oldest')>
                            Más antiguo
                        </option>

                    </select>

                </div>

                {{-- Botones --}}
                <div class="col-lg-2">

                    <div class="d-grid gap-2">

                        <button class="btn btn-primary">

                            <i class="bi bi-search"></i>

                            Buscar

                        </button>

                        <a
                            href="{{ route('admin.historial') }}"
                            class="btn btn-outline-secondary"
                        >

                            <i class="bi bi-arrow-counterclockwise"></i>

                            Limpiar

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</form>