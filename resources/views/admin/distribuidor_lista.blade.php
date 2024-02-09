@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas del sistema')

@section('contenido_principal')
    <h1>Distribuidores</h1>
    <a href="{{ route('distribuidores.create') }}">Crear Distribuidor</a>
    <ul>
        @foreach ($distribuidores as $distribuidor)
            <li>{{$distribuidor->nombre}} <a href="{{route('distribuidores.edit', $distribuidor->id)}}">Editar</a> |
                <form action="{{route('distribuidores.destroy', $distribuidor->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$distribuidores->links()}}
@endsection