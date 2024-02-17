@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la cuenta: <small>{{$publicacion->nombre}}</small></h1>
    <pre>
        <?php var_dump($publicacion); ?>
    </pre>
    <a href="{{ route('publicaciones') }}">Lista de publicaciones</a>

@endsection