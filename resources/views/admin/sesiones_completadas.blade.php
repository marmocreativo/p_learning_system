@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones finalizadas')

@section('contenido_principal')
    <h1>Reporte de finalización de temporadas</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <p>Completados en 2024: {{$total_2024}}</p>
    <table class="table table-stripped">
        <tr>
            <th>#</th>
            <th>Usuario</th>
            <th>email</th>
            <th>Temporada Actual</th>
            <th>Temporada Anterior</th>
            <th>Completada en 2024</th>
        </tr>
        <?php $i=1; ?>
        @foreach ($suscripciones as $suscripcion)
        @php
            $usuario = $usuarios->firstWhere('id', $suscripcion->id_usuario); // Ajusta 'user_id' si tu llave foránea tiene otro nombre
        @endphp
        @if ($suscripcion->champions_a == 'si'||$suscripcion->temporada_completa == 'si')
            <tr>
                <td>{{$i}}</td>
                <td>{{$usuario->nombre}} {{$usuario->apellidos}}</td>
                <td>{{$usuario->email}}</td>
                <td @class([ 'bg-success' => $suscripcion->temporada_completa == 'si', ])>{{$suscripcion->temporada_completa}}</td>
                <td @class([ 'bg-success' => $suscripcion->champions_a == 'si', ])>{{$suscripcion->champions_a}}</td>
                <td >
                @if ($suscripcion->completado_2024)
                    {{'Completado este año'}}
                @else
                    {{'Completado en 2023'}}
                @endif
            </td>
            </tr>
            <?php $i++; ?>
        @endif
               
        @endforeach
    </table>
@endsection