@extends('plantillas/plantilla_admin')

@section('titulo', 'Clases del sistema')

@section('contenido_principal')
    <h1>Detalles configuracion: <small>{{$configuracion->nombre}}</small></h1>
    <pre>
        {{$configuracion}}
    </pre>
    <a href="{{ route('configuraciones') }}">Lista de configuraciones</a>

@endsection