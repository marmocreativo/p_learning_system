@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones')

@section('contenido_principal')
    <h1>Formulario de sesiones</h1>
    <form action="{{ route('sesiones.update', $sesion->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="IdCuenta" value="{{$sesion->id_cuenta}}">
        <input type="hidden" name="IdTemporada" value="{{$sesion->id_temporada}}">
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo</label>
                    <input type="text" class="form-control" name="Titulo" value="{{$sesion->titulo}}">
                </div>
                <div class="form-group">
                    <label for="Url">URL</label>
                    <input type="text" class="form-control" name="Url" value="{{$sesion->url}}">
                </div>
                
                <div class="form-group">
                    <label for="Descripcion">Descripción</label>
                    <textarea class="form-control" name="Descripcion" id="Descripcion" rows="5">{{$sesion->descripcion}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Contenido</label>
                    <textarea class="form-control TextEditor" name="Contenido" id="Contenido" rows="10">{{$sesion->contenido}}</textarea>
                </div>
                <hr>
                
                <!-- Videos -->
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo1">ID Video 1</label>
                            <input type="text" class="form-control" name="IdVideo1" value="{{$sesion->video_1}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo1">Título Video 1</label>
                            <input type="text" class="form-control" name="TituloVideo1" value="{{$sesion->titulo_video_1}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo1Estreno">Puntaje Estreno Video 1</label>
                            <input type="text" class="form-control" name="PuntajeVideo1Estreno" value="{{$sesion->puntaje_video_1_estreno}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo1Normal">Puntaje Normal Video 1</label>
                            <input type="text" class="form-control" name="PuntajeVideo1Normal" value="{{$sesion->puntaje_video_1_normal}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo2">ID Video 2</label>
                            <input type="text" class="form-control" name="IdVideo2" value="{{$sesion->video_2}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo2">Título Video 2</label>
                            <input type="text" class="form-control" name="TituloVideo2" value="{{$sesion->titulo_video_2}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo2Estreno">Puntaje Estreno Video 2</label>
                            <input type="text" class="form-control" name="PuntajeVideo2Estreno" value="{{$sesion->puntaje_video_2_estreno}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo2Normal">Puntaje Normal Video 2</label>
                            <input type="text" class="form-control" name="PuntajeVideo2Normal" value="{{$sesion->puntaje_video_2_normal}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo3">ID Video 3</label>
                            <input type="text" class="form-control" name="IdVideo3" value="{{$sesion->video_3}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo3">Título Video 3</label>
                            <input type="text" class="form-control" name="TituloVideo3" value="{{$sesion->titulo_video_3}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo3Estreno">Puntaje Estreno Video 3</label>
                            <input type="text" class="form-control" name="PuntajeVideo3Estreno" value="{{$sesion->puntaje_video_3_estreno}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo3Normal">Puntaje Normal Video 3</label>
                            <input type="text" class="form-control" name="PuntajeVideo3Normal" value="{{$sesion->puntaje_video_3_normal}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo4">ID Video 4</label>
                            <input type="text" class="form-control" name="IdVideo4" value="{{$sesion->video_4}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo4">Título Video 4</label>
                            <input type="text" class="form-control" name="TituloVideo4" value="{{$sesion->titulo_video_4}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo4Estreno">Puntaje Estreno Video 4</label>
                            <input type="text" class="form-control" name="PuntajeVideo4Estreno" value="{{$sesion->puntaje_video_4_estreno}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo4Normal">Puntaje Normal Video 4</label>
                            <input type="text" class="form-control" name="PuntajeVideo4Normal" value="{{$sesion->puntaje_video_4_normal}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo5">ID Video 5</label>
                            <input type="text" class="form-control" name="IdVideo5" value="{{$sesion->video_5}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo5">Título Video 5</label>
                            <input type="text" class="form-control" name="TituloVideo5" value="{{$sesion->titulo_video_5}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo5Estreno">Puntaje Estreno Video 5</label>
                            <input type="text" class="form-control" name="PuntajeVideo5Estreno" value="{{$sesion->puntaje_video_5_estreno}}">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo5Normal">Puntaje Normal Video 5</label>
                            <input type="text" class="form-control" name="PuntajeVideo5Normal" value="{{$sesion->puntaje_video_5_normal}}">
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>

                <div class="form-group">
                    <label for="ImagenFondo">Imagen Video</label>
                    <input type="file" class="form-control" name="ImagenFondo" >
                </div>
                <hr>
                <h5>Instructor</h5>
                <div class="form-group">
                    <label for="ImagenInstructor">Imagen Instructor</label>
                    <input type="file" class="form-control" name="ImagenInstructor" >
                </div>


                <div class="form-group">
                    <label for="NombreInstructor">Instructor</label>
                    <input type="text" class="form-control" name="NombreInstructor" value="{{$sesion->nombre_instructor}}">
                </div>
                <div class="form-group">
                    <label for="PuestoInstructor">Puesto</label>
                    <input type="text" class="form-control" name="PuestoInstructor" value="{{$sesion->puesto_instructor}}">
                </div>
                <div class="form-group">
                    <label for="BioInstructor">Bio Instructor</label>
                    <textarea class="form-control" name="BioInstructor" id="BioInstructor" rows="5">{{$sesion->bio_instructor}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Correoinstructor">Correo</label>
                    <input type="text" class="form-control" name="Correoinstructor" value="{{$sesion->correo_instructor}}">
                </div>
                <div class="form-group">
                    <label for="DuracionAproximada">Duración Aprox</label>
                    <input type="text" class="form-control" name="DuracionAproximada" value="{{$sesion->duracion_aproximada}}">
                </div>
                <hr>
                <div class="form-group">
                    <label for="CantidadPreguntasEvaluacion">¿Cuantas preguntas se mostraran?</label>
                    <input type="number" class="form-control" name="CantidadPreguntasEvaluacion" value="{{$sesion->cantidad_preguntas_evaluacion}}">
                </div>
                <div class="form-group">
                    <label for="OrdenarPreguntasEvaluacion">¿Cómo se ordenarán las preguntas?</label>
                    <select class="form-control" name="OrdenarPreguntasEvaluacion" id="OrdenarPreguntasEvaluacion">
                        <option value="aleatorio" @if($sesion->orden_preguntas_evaluacion == 'aleatorio') selected @endif>Aleatorio</option>
                        <option value="ordenado" @if($sesion->orden_preguntas_evaluacion == 'ordenado') selected @endif>Ordenado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="EvaluacionObligatoria">¿La evaluación es obligatoria?</label>
                    <select class="form-control" name="EvaluacionObligatoria" id="EvaluacionObligatoria">
                        <option value="si"  @if($sesion->orden_preguntas_evaluacion == 'si') selected @endif>Si</option>
                        <option value="no"  @if($sesion->orden_preguntas_evaluacion == 'no') selected @endif>No</option>
                        
                    </select>
                </div>
                <hr>
                <h5>Puntajes</h5>
                <div class="form-group">
                    <label for="VisualizarPuntajeEstreno">Visualización al estreno</label>
                    <input type="number" class="form-control" name="VisualizarPuntajeEstreno" value="{{$sesion->visualizar_puntaje_estreno}}">
                </div>
                <div class="form-group">
                    <label for="VisualizarPuntajeNormal">Visualización normal</label>
                    <input type="number" class="form-control" name="VisualizarPuntajeNormal" value="{{$sesion->visualizar_puntaje_normal}}">
                </div>
                <div class="form-group">
                    <label for="PreguntasPuntajeEstreno">Puntaje por pregunta al estreno</label>
                    <input type="number" class="form-control" name="PreguntasPuntajeEstreno" value="{{$sesion->preguntas_puntaje_estreno}}">
                </div>
                <div class="form-group">
                    <label for="PreguntasPuntajeNormal">Puntaje por pregunta normal</label>
                    <input type="number" class="form-control" name="PreguntasPuntajeNormal" value="{{$sesion->preguntas_puntaje_normal}}">
                </div>
                <hr>

                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaPublicacion">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaPublicacion" value="{{ date('Y-m-d', strtotime($sesion->fecha_publicacion)) }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraPublicacion">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraPublicacion" value="{{ date('H:i:s', strtotime($sesion->fecha_publicacion)) }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="HorasEstreno">¿Cuantas horas dura el estreno?</label>
                    <input type="number" class="form-control" name="HorasEstreno" value="{{$sesion->horas_estreno}}">
                </div>
                <hr>
                <hr>
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select class="form-control" name="Estado" id="Estado">
                        <option value="inactivo" @if($sesion->estado == 'inactivo') selected @endif>Borrador</option>
                        <option value="activo" @if($sesion->estado == 'activo') selected @endif>Publicado</option>
                    </select>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection