@extends('plantillas/plantilla_admin')

@section('titulo', 'Detalles del usuario')

@section('contenido_principal')
    <h1>Detalles del usuario: <small>{{$usuario->nombre}}</small></h1>
    <table class="table table-stripped">
        <tr>
            <th>Nombre</th>
            <td>{{$usuario->nombre}}</td>
        </tr>
        <tr>
            <th>Apellidos</th>
            <td>{{$usuario->apellidos}}</td>
        </tr>
        <tr>
            <th>Correo</th>
            <td>{{$usuario->email}}</td>
        </tr>
        <tr>
            <th>Telefono</th>
            <td>{{$usuario->telefono}}</td>
        </tr>
        <tr>
            <th>Whatsapp</th>
            <td>{{$usuario->whatsapp}}</td>
        </tr>
        <tr>
            <th>Lista de correo</th>
            <td>{{$usuario->lista_correo}}</td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td>{{$usuario->imagen}}</td>
        </tr>
        <tr>
            <th>Clase</th>
            <td>{{$usuario->clase}}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{$usuario->estado}}</td>
        </tr>
        <tr>
            <th>Registrado</th>
            <td>{{$usuario->created_at}}</td>
        </tr>
        <tr>
            <th>Actualizado</th>
            <td>{{$usuario->updated_at}}</td>
        </tr>
    </table>
    <a href="{{ route('admin_usuarios') }}">Lista de usuarios</a>

@endsection