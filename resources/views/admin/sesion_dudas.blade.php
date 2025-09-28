@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la sesión: <small>{{$sesion->titulo}}</small></h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$sesion->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $sesion->id_temporada)}}">Temporada</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('sesiones', ['id_temporada'=>$sesion->id_temporada]) }}">Sesiones</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('sesiones.show', $sesion->id) }}">{{$sesion->titulo}}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Resultados</li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{route('sesiones.show', $sesion->id)}}" class="btn btn-info">Contenido</a>
                <a href="{{route('sesiones.resultados_excel', ['id_sesion'=>$sesion->id])}}" class="btn btn-success enlace_pesado">Resultados Excel</a>
                <a href="{{route('sesiones.edit', $sesion->id)}}" class="btn btn-warning">Editar sesión</a>
            </div>
        </div>
    </div>
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