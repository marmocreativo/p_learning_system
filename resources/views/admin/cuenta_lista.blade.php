@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas del sistema')

@section('contenido_principal')
    <h1>Cuentas</h1>
    <a href="{{ route('cuentas.create') }}">Crear Cuenta</a>
    <ul>
        @foreach ($cuentas as $cuenta)
            <li>{{$cuenta->nombre}} <a href="{{route('cuentas.edit', $cuenta->id)}}">Editar</a> |
                <form action="{{route('cuentas.destroy', $cuenta->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$cuentas->links()}}
@endsection