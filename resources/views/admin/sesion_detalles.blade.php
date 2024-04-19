@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la sesión: <small>{{$sesion->titulo}}</small></h1>
    <a href="{{ route('sesiones', ['id_temporada'=>$sesion->id_temporada]) }}">Lista de sesiones</a>
    <hr>
    <a href="{{route('sesiones.edit', $sesion->id)}}">Editar sesión</a>
    <hr>
    <div class="row">
        <div class="col-8">
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Título</th>
                    <td>{{$sesion->titulo}}</td>
                </tr>
                <tr>
                    <th>Descripción</th>
                    <td>{{$sesion->descripcion}}</td>
                </tr>
                <tr>
                    <th>Contenido</th>
                    <td>{{$sesion->contenido}}</td>
                </tr>
                <tr>
                    <th>Nombre del instructor</th>
                    <td>{{$sesion->nombre_instructor}}</td>
                </tr>
                <tr>
                    <th>Duración aproximada</th>
                    <td>{{$sesion->duracion_aproximada}}</td>
                </tr>
            </table>
            <hr>
            <h5>Videos</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Video</ 1th>
                    <td>{{$sesion->video_1}}</td>
                    <th>Título</th>
                    <td>{{$sesion->titulo_video_1}}</td>
                </tr>
                <tr>
                    <th>Video 2</th>
                    <td>{{$sesion->video_2}}</td>
                    <th>Título</th>
                    <td>{{$sesion->titulo_video_2}}</td>
                </tr>
                <tr>
                    <th>Video 3</th>
                    <td>{{$sesion->video_3}}</td>
                    <th>Título</th>
                    <td>{{$sesion->titulo_video_3}}</td>
                </tr>
                <tr>
                    <th>Video 4</th>
                    <td>{{$sesion->video_4}}</td>
                    <th>Título</th>
                    <td>{{$sesion->titulo_video_4}}</td>
                </tr>
                <tr>
                    <th>Video 5</th>
                    <td>{{$sesion->video_5}}</td>
                    <th>Título</th>
                    <td>{{$sesion->titulo_video_5}}</td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <h5>Configuraciones</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Estado</th>
                    <td>{{$sesion->estado}}</td>
                </tr>
                <tr>
                    <th>Fecha publicación</th>
                    <td>{{$sesion->fecha_publicacion}}</td>
                </tr>
                <tr>
                    <th>Horas de estreno</th>
                    <td>{{$sesion->horas_estreno}}</td>
                </tr>
                <tr>
                    <th>Cantidad de preguntas para evaluación</th>
                    <td>{{$sesion->cantidad_preguntas_evaluacion}}</td>
                </tr>
                <tr>
                    <th>Ordenar preguntas evaluación</th>
                    <td>{{$sesion->ordenar_preguntas_evaluacion}}</td>
                </tr>
                <tr>
                    <th>Evaluación obligatoria</th>
                    <td>{{$sesion->evaluacion_obligatoria}}</td>
                </tr>
            </table>
            <h5>Puntajes</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Puntaje por:</th>
                    <th>Estreno</th>
                    <th>Normal</th>
                </tr>
                <tr>
                    <td>Visualización</td>
                    <td>{{$sesion->visualizar_puntaje_estreno}}</td>
                    <td>{{$sesion->visualizar_puntaje_normal}}</td>
                </tr>
                <tr>
                    <td>Preguntas</td>
                    <td>{{$sesion->preguntas_puntaje_estreno}}</td>
                    <td>{{$sesion->preguntas_puntaje_normal}}</td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <h4>Preguntas Evaluación</h4>
        <div class="col-4">
            <form action="{{ route('sesiones.store_pregunta') }}" method="POST">
                <input type="hidden" name="IdSesion" value="{{$sesion->id}}">
                @csrf
                <div class="form-group">
                    <label for="Pregunta">Pregunta</label>
                    <input type="text" name="Pregunta" class="form-control">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="RespuestaA">Respuesta A</label>
                            <input type="text" class="form-control" name="RespuestaA">
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="ResultadoA">Resultado A</label>
                        <select name="ResultadoA" class="form-control">
                            <option value="incorrecto">Incorrecto</option>
                            <option value="correcto">Correcto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="RespuestaB">Respuesta B</label>
                            <input type="text" class="form-control" name="RespuestaB">
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="ResultadoB">Resultado B</label>
                        <select name="ResultadoB" class="form-control">
                            <option value="incorrecto">Incorrecto</option>
                            <option value="correcto">Correcto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="RespuestaC">Respuesta C</label>
                            <input type="text" class="form-control" name="RespuestaC">
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="ResultadoC">Resultado C</label>
                        <select name="ResultadoC" class="form-control">
                            <option value="incorrecto">Incorrecto</option>
                            <option value="correcto">Correcto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="RespuestaD">Respuesta D</label>
                            <input type="text" class="form-control" name="RespuestaD">
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="ResultadoD">Resultado D</label>
                        <select name="ResultadoD" class="form-control">
                            <option value="incorrecto">Incorrecto</option>
                            <option value="correcto">Correcto</option>
                        </select>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
        <div class="col-8">
            <table class="table table-bordered">
                <tr>
                    <th>Pregunta</th>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                    <th>Orden</th>
                    <th>Controles</th>
                </tr>
                @foreach ($preguntas as $pregunta)
                <tr>
                    <td>{{$pregunta->pregunta}}</td>
                    <td>
                        <p>{{$pregunta->respuesta_a}}</p>
                        <p><b>{{$pregunta->resultado_a}}</b></p>
                        
                    </td>
                    <td>
                        <p>{{$pregunta->respuesta_b}}</p>
                        <p><b>{{$pregunta->resultado_b}}</b></p>
                        
                    </td>
                    <td>
                        <p>{{$pregunta->respuesta_c}}</p>
                        <p><b>{{$pregunta->resultado_c}}</b></p>
                        
                    </td>
                    <td>
                        <p>{{$pregunta->respuesta_d}}</p>
                        <p><b>{{$pregunta->resultado_d}}</b></p>
                        
                    </td>
                    <td>{{$pregunta->orden}}</td>
                    <td> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#formulario{{$pregunta->id}}">
                            Editar
                        </button>
                        <!-- Modal -->
                            <div class="modal fade" id="formulario{{$pregunta->id}}" tabindex="-1" aria-labelledby="formulario{{$pregunta->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{ route('sesiones.update_pregunta', $pregunta->id) }}" method="POST">
                                            <input type="hidden" name="IdSesion" value="{{$sesion->id}}">
                                            @method('PUT')
                                            @csrf
                                            <div class="form-group">
                                                <label for="Pregunta">Pregunta</label>
                                                <input type="text" name="Pregunta" class="form-control" value="{{$pregunta->pregunta}}">
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="RespuestaA">Respuesta A</label>
                                                        <input type="text" class="form-control" name="RespuestaA" value="{{$pregunta->respuesta_a}}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="ResultadoA">Resultado A</label>
                                                    <select name="ResultadoA" class="form-control">
                                                        <option value="incorrecto" @if($pregunta->resultado_a == 'incorrecto') selected @endif>Incorrecto</option>
                                                        <option value="correcto" @if($pregunta->resultado_a == 'correcto') selected @endif>Correcto</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="RespuestaB">Respuesta B</label>
                                                        <input type="text" class="form-control" name="RespuestaB" value="{{$pregunta->respuesta_b}}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="ResultadoB">Resultado B</label>
                                                    <select name="ResultadoB" class="form-control">
                                                        <option value="incorrecto" @if($pregunta->resultado_b == 'incorrecto') selected @endif>Incorrecto</option>
                                                        <option value="correcto" @if($pregunta->resultado_b == 'correcto') selected @endif>Correcto</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="RespuestaC">Respuesta C</label>
                                                        <input type="text" class="form-control" name="RespuestaC" value="{{$pregunta->respuesta_c}}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="ResultadoC">Resultado C</label>
                                                    <select name="ResultadoC" class="form-control">
                                                        <option value="incorrecto" @if($pregunta->resultado_c == 'incorrecto') selected @endif>Incorrecto</option>
                                                        <option value="correcto" @if($pregunta->resultado_c == 'correcto') selected @endif>Correcto</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="RespuestaD">Respuesta D</label>
                                                        <input type="text" class="form-control" name="RespuestaD" value="{{$pregunta->respuesta_d}}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="ResultadoD">Resultado D</label>
                                                    <select name="ResultadoD" class="form-control">
                                                        <option value="incorrecto" @if($pregunta->resultado_d == 'incorrecto') selected @endif>Incorrecto</option>
                                                        <option value="correcto" @if($pregunta->resultado_d == 'correcto') selected @endif>Correcto</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        <form action="{{route('sesiones.destroy_pregunta', $pregunta->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Borrar</button>
                        </form>
                    </td>
                </tr>
                 @endforeach
            </table>
        </div>
    </div>
    
@endsection