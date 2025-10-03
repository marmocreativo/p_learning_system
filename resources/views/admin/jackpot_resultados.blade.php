@extends('plantillas/plantilla_admin')

@section('titulo', 'Jackpot')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Resultados Minijuego {{$jackpot->titulo}}</h1>
        <div class="d-flex">
            <a href="{{ route('jackpots', ['id_temporada'=>$jackpot->id_temporada]) }}" class="btn btn-success">Lista de minijuegos</a>
            <a href="{{route('jackpots.edit', $jackpot->id)}}" class="btn btn-warning">Editar jackpot</a>
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
            <li class="breadcrumb-item">Minijuegos</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-8">
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Título</th>
                    <td>{{$jackpot->titulo}}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>{{$jackpot->estado}}</td>
                </tr>
                <tr>
                    <th>Fecha publicación</th>
                    <td>{{$jackpot->fecha_publicacion}}</td>
                </tr>
                <tr>
                    <th>Fecha vigencia</th>
                    <td>{{$jackpot->fecha_vigencia}}</td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <h5>Intentos {{count($ganadores)}}</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Ganador</th>
                    
                    <th>Puntaje</th>
                    <th>Fecha</th>
                </tr>
                @foreach ($ganadores as $ganador)
                <tr>
                    <td>{{$ganador->nombre_usuario}} {{$ganador->apellidos}}</td>
                
                    <td>{{$ganador->puntaje}}</td>
                    <td>{{$ganador->fecha_registro}}</td>
                    <td>
                        <form action="{{route('jackpots.destroy_intento', $ganador->id_ganador)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5>Respuestas</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Usuario</th>
                    <th>Pregunta</th>
                    <th>Respuesta</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
                @foreach ($respuestas as $respuesta)
                <tr>
                    <td>{{$respuesta->nombre}} {{$respuesta->apellidos}}</td>
                    <td>{{$respuesta->pregunta}}</td>
                    <td>{{$respuesta->respuesta_usuario}}<br>{{$respuesta->respuesta_resultado}}</td>
                    <td>{{$respuesta->fecha_registro}}</td>
                    <td>
                        <form action="{{route('jackpots.destroy_respuesta', $respuesta->id_respuesta)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    

@endsection