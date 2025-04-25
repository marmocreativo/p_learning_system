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
                  <li class="breadcrumb-item">{{$sesion->titulo}}</li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{route('sesiones.resultados', $sesion->id)}}" class="btn btn-info">Resultados</a>
                <a href="{{route('sesiones.dudas', $sesion->id)}}" class="btn btn-primary">Dudas</a>
                <a href="{{route('sesiones.resultados_excel', ['id_sesion'=>$sesion->id])}}" class="btn btn-success">Resultados Excel</a>
                <a href="{{route('sesiones.edit', $sesion->id)}}" class="btn btn-warning">Editar sesión</a>
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
                    <td>{{$sesion->titulo}}</td>
                    <td rowspan="4">
                        <img class="img-fluid" src="{{ asset('img/publicaciones/'.$sesion->imagen) }}" alt="Ejemplo">
                    </td>
                </tr>
                <tr>
                    <th>Descripción</th>
                    <td>{{$sesion->descripcion}}</td>
                </tr>
                <tr>
                    <th>Contenido</th>
                    <td>{!! $sesion->contenido !!}</td>
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
                    <td colspan="4"> <img class="img-fluid" src="{{ asset('img/publicaciones/'.$sesion->imagen_fondo) }}" alt="Ejemplo"></td>
                </tr>
                <tr>
                    <th>Video 1 </th>
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
            <h5>Instructor</h5>
            <table class="table table-bordered">
                <tr>
                    <td colspan="2"><img class="img-fluid" src="{{ asset('img/publicaciones/'.$sesion->imagen_instructor) }}" alt="Ejemplo"></td>
                </tr>
                <tr>
                    <th>Nombre del instructor</th>
                    <td>{{$sesion->nombre_instructor}}</td>
                </tr>
                <tr>
                    <th>Puesto del instructor</th>
                    <td>{{$sesion->puesto_instructor}}</td>
                </tr>
                <tr>
                    <th>Bio del instructor</th>
                    <td>{{$sesion->bio_instructor}}</td>
                </tr>
                <tr>
                    <th>Correo del instructor</th>
                    <td>{{$sesion->correo_instructor}}</td>
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
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="Video">Pregunta exclusiva del video número...</label>
                            <select name="Video" class="form-control">
                                <option value="">Ningúno</option>
                                <option value="0">Video 1</option>
                                <option value="1">Video 2</option>
                                <option value="2">Video 3</option>
                                <option value="3">Video 4</option>
                                <option value="4">Video 5</option>
                            </select>
                        </div>
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
                    <th>Video</th>
                    <th>Controles</th>
                </tr>
                @foreach ($preguntas as $pregunta)
                <tr>
                    <td>{{$pregunta->pregunta}} ({{$pregunta->id}})</td>
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
                    <td>{{$pregunta->video}}</td>
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
                                            <div class="row">
                                                <div class="col">
                                                    <label for="Video">Esta pregunta pertenece al video</label>
                                                    <select name="Video" class="form-control">
                                                        <option value="" >Ningúno</option>
                                                        <option value="0" @if($pregunta->video == '0') selected @endif>Video 1</option>
                                                        <option value="1" @if($pregunta->video == '1') selected @endif>Video 2</option>
                                                        <option value="2" @if($pregunta->video == '2') selected @endif>Video 3</option>
                                                        <option value="3" @if($pregunta->video == '3') selected @endif>Video 4</option>
                                                        <option value="4" @if($pregunta->video == '4') selected @endif>Video 5</option>
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