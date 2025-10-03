@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
<div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Comentarios sesión: {{$sesion->titulo}} <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{ route('sesiones', ['id_temporada'=> $temporada->id]) }}" class="btn btn-info">Salir</a>
            <a href="{{route('sesiones.resultados', $sesion->id)}}" class="btn btn-info enlace_pesado">Reporte Sesión</a>
            <a href="{{route('sesiones.show', $sesion->id)}}" class="btn btn-primary">Contenido</a>
            <a href="{{route('sesiones.resultados_excel', ['id_sesion'=>$sesion->id])}}" class="btn btn-success enlace_descarga_pesado">Resultados Excel</a>
            <a href="{{route('sesiones.edit', $sesion->id)}}" class="btn btn-warning">Editar sesión</a>
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
            <li class="breadcrumb-item"><a href="{{ route('sesiones', ['id_temporada'=> $temporada->id]) }}">Sesiones</a> </li>
            <li class="breadcrumb-item">{{$sesion->titulo}}</li>
        </ol>
    </nav>
    <hr>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-sm">
                <tr>
                    <th>Usuario</th>
                    <th>Dudas</th>
                    <th>Respuesta</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
                @foreach($dudas as $duda)
                <tr>
                    <td>{{$duda->nombre}} {{$duda->apellidos}}</td>
                    <td>{{$duda->duda}}</td>
                    @if(!empty($duda->respuesta))
                    <td>
                        {{$duda->respuesta}}
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#form-edit{{$duda->id_duda}}" aria-expanded="false" aria-controls="form-edit{{$duda->id_duda}}">
                            Editar
                        </button>
                        <div class="collapse" id="form-edit{{$duda->id_duda}}">
                            <form action="{{route('sesiones.dudas_edit', $duda->id_duda)}}" class="" method="POST">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <textarea name="Respuesta" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                                <button type="submit" class="btn btn-outline-success">Responder</button>
                            </form>
                        </div>
                    </td>
                    @else
                    <td>
                        <form action="{{route('sesiones.dudas_edit', $duda->id_duda)}}" class="" method="POST">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <textarea name="Respuesta" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-success">Responder</button>
                        </form>
                    </td>
                    @endif
                    
                    <td>{{$duda->created_at}}</td>
                    <td>
                        <form action="{{route('sesiones.destroy_dudas', $duda->id_duda)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger">Borrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    
@endsection