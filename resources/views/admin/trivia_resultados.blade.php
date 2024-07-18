@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivia')

@section('contenido_principal')
    <h1>Detalles de la trivia: <small>{{$trivia->titulo}}</small></h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$trivia->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $trivia->id_temporada)}}">Temporada</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('trivias', ['id_temporada'=>$trivia->id_temporada]) }}">Trivias</a></li>
                  <li class="breadcrumb-item"><a href="{{route('trivias.show', $trivia->id)}}">{{$trivia->titulo}}</a></li>
                  <li class="breadcrumb-item">Resultados</li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{route('trivias.show', $trivia->id)}}" class="btn btn-info">Contenido</a>
                <a href="{{route('trivias.resultados_excel', ['id_trivia'=>$trivia->id])}}" class="btn btn-success">Resultados Excel</a>
                <a href="{{route('trivias.edit', $trivia->id)}}" class="btn btn-warning">Editar sesión</a>
            </div>
            
        </div>
    </div>
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
            <h5>Participaciones</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Distribuidor</th>
                    <th>Region</th>
                    @php $i=1; @endphp
                    @foreach ($preguntas as $pregunta)
                        <th>Q{{$i}}</th>
                        @php $i++; @endphp
                    @endforeach
                    <th>Puntaje</th>
                    <th>Fecha</th>
                    <th>Ganador</th>
                    <th>Dirección</th>
                    <th>Telefono</th>
                    <th>Horario</th>
                    <th>Referencia</th>
                    <th>Notas</th>
                    <th>Controles</th>
                </tr>
                @foreach ($participantes as $participante)
                <tr>
                    @php
                         $suscripcion = $suscripciones->first(function ($suscripcion) use ($participante, $trivia) {
                                return $suscripcion->id_usuario == $participante->id_usuario && $suscripcion->id_temporada == $trivia->id_temporada;
                            });
                        
                    @endphp
                    @if(isset($usuarios[$participante->id_usuario])&&!empty($usuarios[$participante->id_usuario]))
                        <td title="{{$participante->id_usuario}}">{{$usuarios[$participante->id_usuario]->nombre}} {{$usuarios[$participante->id_usuario]->apellidos}}</td>
                        <td class='@if($suscripcion) text-success @else text-danger @endif'>{{$usuarios[$participante->id_usuario]->email}}</td>
                    @else
                        <td>Usuario eliminado</td>
                        <td>-</td>
                    @endif

                    @php
                         $respuesta = $respuestas->first(function ($respuesta) use ($participante, $pregunta) {
                                return $respuesta->id_usuario == $participante->id_usuario && $respuesta->id_pregunta == $pregunta->id;
                            });
                        if($respuesta){
                            $distribuidor = $distribuidores->first(function ($distribuidor) use ($respuesta) {
                            return $distribuidor->id == $respuesta->id_distribuidor;
                        });
                        }else{
                            $distribuidor = null;
                        }
                        
                    @endphp

                    @if(isset($distribuidor)&&!empty($distribuidor))
                        <td>{{$distribuidor->nombre}}</td>
                        <td>{{$distribuidor->region}}</td>
                    @else
                        <td>Sin distribuidor</td>
                        <td>-</td>
                    @endif
                    @php $puntaje = 0; @endphp
                    @foreach ($preguntas as $pregunta)
                        @php
                            $respuesta = $respuestas->first(function ($respuesta) use ($participante, $pregunta) {
                                return $respuesta->id_usuario == $participante->id_usuario && $respuesta->id_pregunta == $pregunta->id;
                            });
                            if(!empty($respuesta)){
                                $puntaje += $respuesta->puntaje;
                            }
                        @endphp
                        <td>
                            @if ($respuesta)
                                {{ $respuesta->respuesta_usuario }}
                                @if($respuesta->respuesta_correcta=='correcto') <i class="fa-solid fa-circle-check"></i> @else <i class="fa-solid fa-circle-xmark"></i> @endif
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    <td>
                        {{$puntaje}}
                    </td>
                    @php
                        $respuesta = $respuestas->first(function ($respuesta) use ($participante) {
                            return $respuesta->id_usuario == $participante->id_usuario;
                        });
                        
                    @endphp
                    <td>{{$respuesta->fecha_registro}}</td>
                    @php
                        $ganador = $ganadores->first(function ($ganadores) use ($participante) {
                            return $ganadores->id_usuario == $participante->id_usuario;
                        });
                        
                    @endphp
                    <td>
                        @if ($ganador)
                            Ganador
                        @else
                            -
                        @endif
                    </td>
                        @if ($ganador)
                            <td>
                                {{$ganador->direccion_nombre}},
                                {{$ganador->direccion_calle}},
                                {{$ganador->direccion_numero}},
                                {{$ganador->direccion_numeroint}},
                                {{$ganador->direccion_colonia}},
                                {{$ganador->direccion_ciudad}},
                                {{$ganador->direccion_delegacion}},
                                {{$ganador->direccion_codigo_postal}}
                            </td>
                            <td>{{$ganador->direccion_telefono}} </td>
                            <td>{{$ganador->direccion_horario}} </td>
                            <td>{{$ganador->direccion_referencia}}</td>
                            <td>{{$ganador->direccion_notas}}</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        @endif
                        <td>
                            <form action="{{route('trivias.destroy_participacion')}}" class="form-confirmar" method="POST">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="IdUsuario" value="{{$participante->id_usuario}}">
                                <input type="hidden" name="IdTrivia" value="{{$trivia->id}}">
                                <button type="submit" class="btn btn-link">Borrar</button>
                            </form>
                        </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    

@endsection