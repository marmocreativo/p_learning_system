@extends('plantillas/plantilla_admin')

@section('titulo', 'Detalles del usuario')

@section('contenido_principal')
    @if($usuario)
    <h1>Reporte del usuario: <small>{{$usuario->nombre}}</small></h1>
    <div class="row">
        <div class="col-3">
            <div class="card card-body">
                <table class="table table-stripped table-sm">
                    <tr>
                        <th>Cuenta</th>
                        <td>{{$cuenta->nombre}}</td>
                        <td>{{$cuenta->id}}</td>
                    </tr>
                    <tr>
                        <th>Temporada</th>
                        <td>{{$temporada->nombre}}</td>
                        <td>{{$temporada->id}}</td>
                    </tr>
                </table>
            </div>
            <div class="card card-body">
                <table class="table table-stripped table-sm">
                     <tr>
                        <th>ID</th>
                        <td>{{$usuario->id}}</td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td>{{$usuario->nombre}} {{$usuario->apellidos}}</td>
                    </tr>
                    <tr>
                        <th>Correo</th>
                        <td>{{$usuario->email}}</td>
                    </tr>
                    <tr>
                        <th>ID Suscripción</th>
                        <td>{{$suscripcion->id}}</td>
                    </tr>
                    <tr>
                        <th>Nivel usuario</th>
                        <td>{{$suscripcion->nivel_usuario}}</td>
                    </tr>
                    <tr>
                        <th>Función</th>
                        <td>{{$suscripcion->funcion}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-9">
            <div class="card card-body mb-3">
                <table class="table">
                    <thead>
                        <th>Sesiones</th>
                        <th>Evaluaciones</th>
                        <th>Trivias</th>
                        <th>Minijuegos</th>
                        <th>Extra</th>
                    </thead>
                    <tbody>
                        <td>{{$suma_visualizaciones}}</td>
                        <td>{{$suma_evaluaciones}}</td>
                        <td>{{$suma_trivias}}</td>
                        <td>{{$suma_jackpots}}</td>
                        <td>{{$suma_extra}}</td>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="card card-body mb-3">
                <h3>Sesiones</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Visualizacion</th>
                            <th>Evaluacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sesiones as $sesion)
                            <tr>
                                <td>{{$sesion->titulo}}</td>
                                <td>
                                    <table class="table table-sm">
                                        <tr> <th>V1</th> <td>{{$sesion->fecha_video_1}}</td> </tr>
                                        <tr> <th>V2</th> <td>{{$sesion->fecha_video_2}}</td> </tr>
                                        <tr> <th>V3</th> <td>{{$sesion->fecha_video_3}}</td> </tr>
                                        <tr> <th>V4</th> <td>{{$sesion->fecha_video_4}}</td> </tr>
                                        <tr> <th>V5</th> <td>{{$sesion->fecha_video_5}}</td> </tr>
                                    </table>
                                    <hr>
                                    <table class="table table-bordered table-sm">
                                            <tr> <th>Completado</th> <td>{{$sesion->fecha_ultimo_video}}</td> <td>{{$sesion->puntaje}}</td> </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <th>#</th>
                                            <th>Resupesta</th>
                                            <th>Correcto</th>
                                            <th>Puntaje</th>
                                            <th>Fecha</th>
                                        </thead>
                                        <tbody>
                                            @php
                                                $letras = ['A', 'B', 'C', 'D'];
                                                $i = 0;
                                            @endphp
                                            @foreach ($evaluaciones as $evaluacion)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$evaluacion->respuesta_usuario}}</td>
                                                    <td>{{$evaluacion->respuesta_correcta}}</td>
                                                    <td>{{$evaluacion->puntaje}}</td>
                                                    <td>{{$evaluacion->fecha_registro}}</td>
                                                </tr>
                                                @php
                                                    $i ++;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="card card-body mb-3">
                <h3>Trivias Respuestas</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Trivia</th>
                            <th>Pregunta</th>
                            <th>Respuesta</th>
                            <th>Correcto</th>
                            <th>Puntaje</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trivias_respuestas as $respuesta)
                            <tr>
                                <td>{{$respuesta->titulo}}</td>
                                <td>{{$respuesta->pregunta}}</td>
                                <td>{{$respuesta->respuesta_usuario}}</td>
                                <td>{{$respuesta->respuesta_correcta}}</td>
                                <td>{{$respuesta->puntaje}}</td>
                                <td>{{$respuesta->fecha_registro}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card card-body mb-3">
                <h3>Trivias Ganadas</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Trivia</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trivias_ganadores as $ganador)
                            <tr>
                                <td>{{$ganador->titulo}}</td>
                                <td>{{$ganador->fecha_registro}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="card card-body mb-3">
                <h3>Minijuegos</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Juego</th>
                            <th>Tipo</th>
                            <th>Tiro</th>
                            <th>Slot 1</th>
                            <th>Slot 2</th>
                            <th>Slot 3</th>
                            <th>Puntaje</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jackpot_intentos as $intento)
                            <tr>
                                <td>{{$intento->titulo}}</td>
                                <td>{{$intento->tipo}}</td>
                                <td>{{$intento->tiro}}</td>
                                <td>{{$intento->slot_1}}</td>
                                <td>{{$intento->slot_2}}</td>
                                <td>{{$intento->slot_3}}</td>
                                <td>{{$intento->puntaje}}</td>
                                <td>{{$intento->fecha_registro}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="card card-body mb-3">
                <h3>Acciones</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Accion</th>
                            <th>Descripcion</th>
                            <th>ID Temporada</th>
                            <th>ID Cuenta</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($acciones as $accion)
                        <tr>
                            <td>{{$accion->accion}}</td>
                            <td>{{$accion->descripcion}}</td>
                            <td>{{$accion->id_temporada}}</td>
                            <td>{{$accion->id_cuenta}}</td>
                            <td>{{$accion->created_at}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @endif

@endsection