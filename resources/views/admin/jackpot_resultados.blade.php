@extends('plantillas/plantilla_admin')

@section('titulo', 'Jackpot')

@section('contenido_principal')
    <h1>Resultado del Minijuego: <small>{{$jackpot->titulo}}</small></h1>
    <a href="{{ route('jackpots', ['id_temporada'=>$jackpot->id_temporada]) }}">Lista de minijuegos</a>
    <hr>
    <a href="{{route('jackpots.edit', $jackpot->id)}}">Editar jackpot</a>
    <hr>
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