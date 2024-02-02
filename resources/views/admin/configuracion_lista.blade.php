@extends('plantillas/plantilla_admin')

@section('titulo', 'Configuraciones Globales')

@section('contenido_principal')
    <h1>Configuraciones globales del sistema</h1>
    <a href="{{ route('configuraciones.create') }}">Crear configuración</a>
    <ul>
        @foreach ($configuraciones as $configuracion)
            <li>{{$configuracion->nombre}} <a href="{{route('configuraciones.edit', $configuracion->id)}}">Editar</a> |
                <form action="{{route('configuraciones.destroy', $configuracion->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
@endsection