@extends('plantillas/plantilla_admin')

@section('titulo', 'Notificaciones')

@section('contenido_principal')
    <h1>Notificaciones</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('notificaciones.create', ['id_temporada'=>$_GET['id_temporada']]) }}">Crear Notificacion</a>
    <hr>
    <ul>
        @foreach ($notificaciones as $notificacion)
            <li>{{$notificacion->titulo}} 
                <a href="{{route('notificaciones.show', $notificacion->id)}}">Ver detalles</a> |
                <a href="{{route('notificaciones.edit', $notificacion->id)}}">Editar</a> |
                <form action="{{route('notificaciones.destroy', $notificacion->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$notificaciones->links()}}
@endsection