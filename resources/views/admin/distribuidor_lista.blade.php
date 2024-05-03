@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas del sistema')

@section('contenido_principal')
    <h1>Distribuidores</h1>
    <a href="{{ route('distribuidores.create') }}">Crear Distribuidor</a>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>PAIS</th>
            <th>REGION</th>
            <th>DEFAULT PASS</th>
            <th>NIVEL</th>
            <th>CONTROLES</th>
        </tr>
    
        @foreach ($distribuidores as $distribuidor)
            <tr>
                <td>{{$distribuidor->id}}</td>
                <td>{{$distribuidor->nombre}}</td>
                <td>{{$distribuidor->pais}}</td>
                <td>{{$distribuidor->region}}</td>
                <td>{{$distribuidor->default_pass}}</td>
                <td>{{$distribuidor->nivel}}</td>
                <td>
                    <a href="{{route('distribuidores.show', $distribuidor->id)}}">Ver detalles</a> |
                    <a href="{{route('distribuidores.edit', $distribuidor->id)}}">Editar</a> |
                </td>
                <td>
                    <form action="{{route('distribuidores.destroy', $distribuidor->id)}}" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-link">Borrar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    {{$distribuidores->links()}}
@endsection