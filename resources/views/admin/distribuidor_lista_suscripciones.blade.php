@extends('plantillas/plantilla_admin')

@section('titulo', 'Distribuidores participantes')

@section('contenido_principal')
    <h1>Distribuidores suscritos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('distribuidores_suscritos.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}">Suscribir distribuidor</a>
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
                    <td>{{$suscripcion->id_distribuidor}}</td>
                    <td>{{$suscripcion->nombre}}</td>
                    <td>{{$suscripcion->pais}} </td>
                    <td>{{$suscripcion->region}}</td>
                    <td>{{$suscripcion->nivel}}</td>
                    <td>
                        <form action="{{route('distribuidores_suscritos.desuscribir', $suscripcion->id)}}" method="POST">
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