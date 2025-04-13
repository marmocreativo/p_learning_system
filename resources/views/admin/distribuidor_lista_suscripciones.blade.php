@extends('plantillas/plantilla_admin')

@section('titulo', 'Distribuidores participantes')

@section('contenido_principal')
    <h1>Distribuidores suscritos</h1>
    <h4><b>Cuenta:</b> {{$cuenta->nombre}} <b>Temporada:</b> {{$temporada->nombre}}</h4>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('distribuidores_suscritos.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}">Suscribir distribuidor</a>
    <hr>
    <div class="row">
        <div class="col-4">
        </div>
        <div class="col-4">
            <form action="{{ route('imp_distribuidores_2025') }}" class="d-flex" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_temporada" value="{{$_GET['id_temporada']}}">
                <div class="form-group">
                    <label for="file">Distribuidores</label>
                    <input type="file" name="file" accept=".xlsx" placeholder="Importar distribuidores">
                </div>
                <button type="submit" class="btn btn-primary">Importar</button>
            </form>
        </div>
        <div class="col-4">
            <form action="{{ route('imp_sucursales_2025') }}" class="d-flex" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_temporada" value="{{$_GET['id_temporada']}}">
                <div class="form-group">
                    <label for="file">Sucursales</label>
                    <input type="file" name="file" accept=".xlsx" placeholder="Importar sucursales">
                </div>
                <button type="submit" class="btn btn-primary">Importar</button>
            </form>
        </div>
    </div>
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