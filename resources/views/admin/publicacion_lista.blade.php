@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Publicaciones <b>{{$_GET['clase']?? $_GET['clase']}}</b> <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group">
            <a href="{{ route('publicaciones.create', ['id_temporada'=>$_GET['id_temporada'], 'clase'=>$_GET['clase']]) }}" class="btn btn-success">Crear Publicaciones</a>
            <a href="{{ route('publicaciones.reporte_clicks', ['id_temporada'=>$_GET['id_temporada']]) }}" class="btn btn-outline-success">Descargar reporte</a>
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
            <li class="breadcrumb-item">Publicaciones</li>
        </ol>
    </nav>
    
    
    <hr>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>TITULO</th>
            <th>FUNCION</th>
            <th>CONTROLES</th>
            <th>BORRAR</th>
        </tr>
    
        @foreach ($publicaciones as $publicacion)
            <tr>
                <td>{{$publicacion->id}}</td>
                <td>{{$publicacion->titulo}}</td>
                <td>{{$publicacion->funcion}}</td>
                
                <td>
                    <a href="{{route('publicaciones.show', $publicacion->id)}}">Ver detalles</a> |
                    <a href="{{route('publicaciones.edit', $publicacion->id)}}">Editar</a> |
                </td>
                <td>
                    <form action="{{route('publicaciones.destroy', $publicacion->id)}}" class="form-confirmar" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-link">Borrar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    {{$publicaciones->appends(['id_temporada' =>$_GET['id_temporada'], 'clase' =>$_GET['clase']])->links()}}
@endsection