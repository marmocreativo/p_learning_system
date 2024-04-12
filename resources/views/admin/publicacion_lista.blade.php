@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Publicaciones</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('publicaciones.create', ['id_temporada'=>$_GET['id_temporada'], 'clase'=>$_GET['clase']]) }}">Crear Publicaciones</a>
    <hr>
    <ul>
        @foreach ($publicaciones as $publicacion)
            <li>{{$publicacion->titulo}} 
                <a href="{{route('publicaciones.show', $publicacion->id)}}">Ver detalles</a> |
                <a href="{{route('publicaciones.edit', $publicacion->id)}}">Editar</a> |
                <form action="{{route('publicaciones.destroy', $publicacion->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$publicaciones->links()}}
@endsection