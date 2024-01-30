@extends('plantillas/plantilla_admin')

@section('titulo', 'Clases del sistema')

@section('contenido_principal')
    <h1>Detalles clase: <small>{{$clase->nombre_singular}}</small></h1>
    <table class="table table-stripped">
        <tr>
            <td>Nombre Sistema</td>
            <td>{{$clase->nombre_sistema}}</td>
        </tr>
        <tr>
            <td>Nombre Singular</td>
            <td>{{$clase->nombre_singular}}</td>
        </tr>
        <tr>
            <td>Nombre Plural</td>
            <td>{{$clase->nombre_plural}}</td>
        </tr>
        <tr>
            <td>Elementos</td>
            <td>{{$clase->elementos}}</td>
        </tr>
    </table>
    <a href="{{ route('clases') }}">Lista de clases</a>

@endsection