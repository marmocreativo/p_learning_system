@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios registrados')

@section('contenido_principal')
    <h1>Usuarios registrados</h1>
    <a href="{{ route('admin_usuarios.create') }}">Registrar Usuario</a>
    <hr>
    <div class="row">
        <div class="col-6">
            <form class="d-flex" action="{{ route('admin_usuarios') }}" method="GET">
                <div class="form-group me-2">
                    <input type="text" class="form-control" name="search" placeholder="Buscar Correo...">
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>CORREO</th>
                <th>EDITAR</th>
                <th>BORRAR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{$usuario->id}}</td>
                    <td>
                        {{$usuario->nombre}} {{$usuario->apellidos}}
                    </td>
                    <td>{{$usuario->email}}</td>
                    <td>
                        <a href="{{route('admin_usuarios.edit', $usuario->id)}}">Editar</a>
                    </td>
                    <td>
                        <form action="{{route('admin_usuarios.destroy', $usuario->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    {{$usuarios->links()}}
@endsection