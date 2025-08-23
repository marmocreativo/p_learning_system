@extends('plantillas/plantilla_admin')

@section('titulo', 'Distribuidores participantes')

@section('contenido_principal')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Distribuidores participantes <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{ route('distribuidores_suscritos.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}">Suscribir distribuidor</a>
        </div>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button"  data-mdb-dropdown-init
                        data-mdb-ripple-init>
                    Cuentas
                </a>
                <ul class="dropdown-menu" aria-labelledby="breadcrumbDropdown">
                    @foreach($cuentas as $cuentaItem)
                        <li>
                            <a class="dropdown-item" href="{{ route('temporadas', ['id_cuenta' => $cuentaItem->id]) }}">
                                {{ $cuentaItem->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">{{$temporada->nombre}}</a> </li>
            <li class="breadcrumb-item">Distribuidores Suscritor</li>
        </ol>
    </nav>
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