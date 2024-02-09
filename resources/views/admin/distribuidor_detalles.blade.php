@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles del distribuidor: <small>{{$distribuidor->nombre}}</small></h1>
    <table class="table table-stripped">
        <tr>
            <td>Nombre</td>
            <td>{{$distribuidor->nombre}}</td>
        </tr>
        <tr>
            <td>Pais</td>
            <td>{{$distribuidor->pais}}</td>
        </tr>
        <tr>
            <td>Region</td>
            <td>{{$distribuidor->region}}</td>
        </tr>
        <tr>
            <td>Nivel</td>
            <td>{{$distribuidor->nivel}}</td>
        </tr>
        <tr>
            <td>Estado</td>
            <td>{{$distribuidor->estado}}</td>
        </tr>

    </table>
    <a href="{{ route('distribuidores') }}">Lista de distribuidores</a>

@endsection