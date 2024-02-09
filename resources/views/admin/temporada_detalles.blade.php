@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la temporada <small>{{$temporada->nombre}}</small></h1>
    <table class="table table-stripped">
        <tr>
            <td>Nombre</td>
            <td>{{$temporada->nombre}}</td>
        </tr>
        <tr>
            <td>Descripción</td>
            <td>{{$temporada->descripcion}}</td>
        </tr>
        <tr>
            <td>Titulo para Landing</td>
            <td>{{$temporada->titulo_landing}}</td>
        </tr>
        <tr>
            <td>Mensaje para Landing</td>
            <td>{{$temporada->mensaje_landing}}</td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td>{{$temporada->fecha_inicio}}</td>
        </tr>
        <tr>
            <td>Fecha Final</td>
            <td>{{$temporada->fecha_final}}</td>
        </tr>

    </table>
    <a href="{{ route('temporadas') }}">Lista de temporadas</a>

@endsection