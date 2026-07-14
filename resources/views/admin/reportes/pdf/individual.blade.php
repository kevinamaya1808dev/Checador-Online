<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Reporte Individual - OllinCheck</title>


<style>

body {

    font-family: DejaVu Sans, sans-serif;

    font-size: 12px;

    color: #1f2937;

}


.header {

    background-color: #111827;

    color: white;

    padding: 15px;

    text-align: center;

}


.header h1 {

    margin:0;

    font-size:22px;

}


.header p {

    margin-top:5px;

}



.section-title {

    margin-top:20px;

    background:#374151;

    color:white;

    padding:8px;

    font-size:14px;

}



.info-table,
.summary-table,
.data-table {

    width:100%;

    border-collapse:collapse;

    margin-top:10px;

}



.info-table td {

    padding:8px;

    border:1px solid #ddd;

}



.summary-table td {

    border:1px solid #ddd;

    text-align:center;

    padding:10px;

}



.summary-title {

    color:#6b7280;

    font-size:11px;

}



.summary-value {

    font-size:16px;

    font-weight:bold;

}



.data-table th {

    background:#2563eb;

    color:white;

    padding:8px;

    border:1px solid #ddd;

}



.data-table td {

    padding:7px;

    border:1px solid #ddd;

    text-align:center;

}



.pause-box {

    margin-top:5px;

    font-size:10px;

}



.footer {

    position:fixed;

    bottom:0;

    width:100%;

    text-align:center;

    font-size:10px;

    color:#6b7280;

}


</style>

</head>



<body>



<div class="header">

<h1>OLLIN CHECK</h1>

<p>
Reporte individual de asistencia
</p>

</div>




<div class="section-title">

Información del becario

</div>


<table class="info-table">

<tr>

<td>
<strong>Nombre:</strong>
{{ $user->name }}
</td>


<td>
<strong>Correo:</strong>
{{ $user->email }}
</td>


</tr>


</table>




<div class="section-title">

Resumen del periodo

</div>



<table class="summary-table">

<tr>


<td>

<div class="summary-title">
Jornadas
</div>

<div class="summary-value">
{{ $resumen['jornadas'] }}
</div>


</td>



<td>

<div class="summary-title">
Tiempo trabajado
</div>

<div class="summary-value">
{{ $resumen['horas_trabajadas'] }}
</div>


</td>



<td>

<div class="summary-title">
Pausas
</div>

<div class="summary-value">
{{ $resumen['tiempo_pausa'] }}
</div>


</td>



<td>

<div class="summary-title">
Horas extra
</div>

<div class="summary-value">
{{ $resumen['horas_extra'] }}
</div>


</td>



</tr>

</table>





<div class="section-title">

Detalle de jornadas

</div>




<table class="data-table">


<thead>

<tr>

<th>
Fecha
</th>


<th>
Entrada
</th>


<th>
Salida
</th>


<th>
Pausas
</th>


<th>
Trabajo
</th>


<th>
Extra
</th>


</tr>

</thead>


<tbody>


@foreach($asistencias as $asistencia)


<tr>


<td>
{{ $asistencia->fecha }}
</td>


<td>
{{ $asistencia->hora_entrada ?? '--' }}
</td>


<td>
{{ $asistencia->hora_salida ?? '--' }}
</td>


<td>
{{ $asistencia->tiempoPausas() }}
</td>


<td>

{{ 
$asistencia->formatoTiempo(
    $asistencia->tiempoTrabajado()
)
}}

</td>


<td>
{{ $asistencia->horasExtrasTotalFormato() }}
</td>


</tr>


@endforeach


</tbody>


</table>




<div class="footer">

OLLIN CHECK |

Generado:

{{ now()->format('d/m/Y H:i') }}

</div>



</body>

</html>