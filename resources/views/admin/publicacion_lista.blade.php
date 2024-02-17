@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Publicaciones</h1>
    <a href="{{ route('publicaciones.create') }}">Crear Publicaciones</a>
    <ul>
        @foreach ($publicaciones as $publicacion)
            <li>{{$publicacion->nombre}} <a href="{{route('publicaciones.edit', $publicacion->id)}}">Editar</a> |
                <form action="{{route('publicaciones.destroy', $publicacion->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$publicaciones->links()}}
@endsection