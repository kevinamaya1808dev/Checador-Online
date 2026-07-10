<div
class="modal fade"
id="detalleJornada{{ $asistencia->id }}"
tabindex="-1"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content bg-dark text-light border-secondary">

<div class="modal-header border-secondary">

<h5 class="modal-title">

<i class="bi bi-clock-history text-primary"></i>

Detalle de Jornada

</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal"
></button>

</div>

<div class="modal-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="text-secondary">

Becario

</label>

<h5>

{{ $asistencia->user->name }}

</h5>

<small>

{{ $asistencia->user->email }}

</small>

</div>

<div class="col-md-6 mb-3">

<label class="text-secondary">

Fecha

</label>

<h5>

{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}

</h5>

</div>

<div class="col-md-4">

<label class="text-secondary">

Entrada

</label>

<h6>

{{ \Carbon\Carbon::parse($asistencia->hora_entrada)->format('h:i:s A') }}

</h6>

</div>

<div class="col-md-4">

<label class="text-secondary">

Salida

</label>

<h6>

@if($asistencia->hora_salida)

{{ \Carbon\Carbon::parse($asistencia->hora_salida)->format('h:i:s A') }}

@else

--

@endif

</h6>

</div>

<div class="col-md-4">

<label class="text-secondary">

Horas extra

</label>

<h6 class="text-success">

{{ $asistencia->horasExtrasTotalFormato() }}

</h6>

</div>

</div>

<hr class="border-secondary">

<div class="row text-center">

<div class="col">

<label class="text-secondary">

Tiempo trabajado

</label>

<h4 class="text-info">

{{ $asistencia->formatoTiempo($asistencia->tiempoTrabajado()) }}

</h4>

</div>

<div class="col">

<label class="text-secondary">

Tiempo descanso

</label>

<h4 class="text-warning">

{{ $asistencia->tiempoPausas() }}

</h4>

</div>

</div>

<hr class="border-secondary">

<h5 class="mb-3">

<i class="bi bi-cup-hot"></i>

Pausas

</h5>

@if($asistencia->pausas->isEmpty())

<div class="alert alert-secondary">

No hubo pausas.

</div>

@else

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

<tbody>

@foreach($asistencia->pausas as $pausa)

<tr>

<td>

{{ $loop->iteration }}

</td>

<td>

{{ $pausa->motivo }}

</td>

<td>

{{ \Carbon\Carbon::parse($pausa->inicio_pausa)->format('h:i:s A') }}

</td>

<td>

@if($pausa->fin_pausa)

{{ \Carbon\Carbon::parse($pausa->fin_pausa)->format('h:i:s A') }}

@else

--

@endif

</td>

<td>

{{ gmdate('H:i:s',$pausa->duracion()) }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endif

</div>

<div class="modal-footer border-secondary">

<button
class="btn btn-secondary"
data-bs-dismiss="modal"
>

Cerrar

</button>

</div>

</div>

</div>

</div>