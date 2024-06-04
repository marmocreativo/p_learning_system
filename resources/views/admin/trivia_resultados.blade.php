@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivia')

@section('contenido_principal')
    <h1>Detalles de la trivia: <small>{{$trivia->titulo}}</small></h1>
    <a href="{{ route('trivias', ['id_temporada'=>$trivia->id_temporada]) }}">Lista de trivias</a>
    <hr>
    <a href="{{route('trivias.edit', $trivia->id)}}">Editar trivia</a>
    <hr>
    <div class="row">
        <div class="col-8">
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Título</th>
                    <td>{{$trivia->titulo}}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>{{$trivia->estado}}</td>
                </tr>
                <tr>
                    <th>Puntaje por pregunta</th>
                    <td>{{$trivia->puntaje}}</td>
                </tr>
                <tr>
                    <th>Fecha publicación</th>
                    <td>{{$trivia->fecha_publicacion}}</td>
                </tr>
                <tr>
                    <th>Fecha vigencia</th>
                    <td>{{$trivia->fecha_vigencia}}</td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <table class="table table-bordered">
                    <tr>
                        <td>Participantes: {{$numero_participantes}}</td>
                        <td>Ganadores: {{$numero_ganadores}}</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5>Ganadores</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Ganador</th>
                    <th>Distribuidor</th>
                    <th>Dirección</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
                @foreach ($ganadores as $ganador)
                <tr>
                    <td>{{$ganador->nombre_usuario}} {{$ganador->apellidos}}</td>
                    <td>{{$ganador->nombre_distribuidor}}</td>
                    <td>
                            @if($ganador->direccion_confirmada)
                            <p><b>{{ $ganador->direccion_nombre }}</b> {{ $ganador->direccion_calle }}, {{ $ganador->direccion_numero }},{{ $ganador->direccion_numeroint }}, {{ $ganador->direccion_colonia }}, {{ $ganador->direccion_ciudad }}
                                {{ $ganador->direccion_codigo_postal }}, Horario: {{ $ganador->direccion_horario }}, Referencia: {{ $ganador->direccion_referencia }}, Notas: {{ $ganador->direccion_notas }}
                            </p>
                            @else
                            <p>Dirección no confirmada</p>
                            @endif
                    </td>
                    <td>{{$ganador->fecha_registro}}</td>
                    <td>
                        <form action="{{route('trivias.destroy_ganador', $ganador->id_ganador)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
            <hr>
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
                        <form action="{{route('trivias.destroy_respuesta', $respuesta->id_respuesta)}}" class="form-confirmar" method="POST">
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