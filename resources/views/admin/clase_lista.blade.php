@extends('plantillas/plantilla_admin')

@section('titulo', 'Clases del sistema')

@section('contenido_principal')
    <h1>Clases del sistema</h1>
    <a href="{{ route('clases.create') }}">Crear Clase</a>
    <ul>
        @foreach ($clases as $clase)
            <li>{{$clase->nombre_singular}} <a href="{{route('clases.edit', $clase->id)}}">Editar</a> |
                <form action="{{route('clases.destroy', $clase->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$clases->links()}}
@endsection