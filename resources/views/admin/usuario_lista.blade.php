@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios registrados')

@section('contenido_principal')
    <h1>Usuarios registrados</h1>
    <a href="{{ route('admin_usuarios.create') }}">Registrar Usuario</a>
    <ul>
        @foreach ($usuarios as $usuario)
            <li>{{$usuario->nombre}} {{$usuario->apellidos}} | {{$usuario->email}} <a href="{{route('admin_usuarios.edit', $usuario->id)}}">Editar</a> |
                <form action="{{route('admin_usuarios.destroy', $usuario->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$usuarios->links()}}
@endsection