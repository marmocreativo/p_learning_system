@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios inscritos')

@section('contenido_principal')
    <h1>Usuarios inscritos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('admin_usuarios.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}">Inscribir usuario</a>
    <hr>
    <table class="table table-stripped">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Distribuidor</th>
            <th>Permisos</th>
            <th>Controles</th>
        </tr>
        @foreach ($suscripciones as $suscripcion)
                <tr>
                    <td>{{$suscripcion->id_usuario}}</td>
                    <td>{{$suscripcion->nombre}}</td>
                    <td>{{$suscripcion->email}} </td>
                    <td>{{$suscripcion->id_distribuidor}}</td>
                    <td>
                        {{$suscripcion->funcion}}
                        @if($suscripcion->funcion === 'usuario')
                            <a href="{{ route('admin_usuarios.cambiar_a_lider', ['id' => $suscripcion->id, 'id_temporada' => $_GET['id_temporada']]) }}">Cambiar a líder</a>
                        @else
                            <a href="{{ route('admin_usuarios.cambiar_a_usuario', ['id' => $suscripcion->id, 'id_temporada' => $_GET['id_temporada']]) }}">Cambiar a usuario</a>
                        @endif
                    </td>
                    <td>
                        <form action="{{route('admin_usuarios.desuscribir', $suscripcion->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="id_temporada" value='{{$_GET['id_temporada']}}'>
                            <button type="submit" class="btn btn-link">Desuscribir</button>
                        </form>
                    </td>
                </tr>
        @endforeach
    </table>
@endsection