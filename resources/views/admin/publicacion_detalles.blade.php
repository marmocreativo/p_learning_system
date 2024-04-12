@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Detalles de la publicacion: <small>{{$publicacion->titulo}}</small></h1>
    <pre>
        <?php var_dump($publicacion); ?>
    </pre>
    <a href="{{ route('publicaciones', ['id_temporada'=>$publicacion->id_temporada, 'clase'=>$publicacion->clase]) }}">Lista de publicaciones</a>

@endsection