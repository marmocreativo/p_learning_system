@extends('plantillas/plantilla_admin')

@section('titulo', 'Temporadas')

@section('contenido_principal')
    <h1>Temporada</h1>
    <a href="{{ route('temporadas.create') }}">Crear Temporada</a>
    <ul>
        @foreach ($temporadas as $temporada)
            <li>{{$temporada->nombre}} <a href="{{route('temporadas.edit', $temporada->id)}}">Editar</a> |
                <form action="{{route('temporadas.destroy', $temporada->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$temporadas->links()}}
@endsection