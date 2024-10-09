@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones')

@section('contenido_principal')
    <h1>Formulario de sesiones</h1>
    <form action="{{ route('sesiones.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
        <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo</label>
                    <input type="text" class="form-control" name="Titulo">
                </div>
                
                <div class="form-group">
                    <label for="Descripcion">Descripción</label>
                    <textarea class="form-control" name="Descripcion" id="Descripcion" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Contenido</label>
                    <textarea class="form-control TextEditor" name="Contenido" id="Contenido" rows="10"></textarea>
                </div>
                <hr>
                
                <!-- Videos -->
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo1">ID Video 1</label>
                            <input type="text" class="form-control" name="IdVideo1">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo1">Título Video 1</label>
                            <input type="text" class="form-control" name="TituloVideo1">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo1Estreno">Puntaje Estreno Video 1</label>
                            <input type="text" class="form-control" name="PuntajeVideo1Estreno">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo1Normal">Puntaje Normal Video 1</label>
                            <input type="text" class="form-control" name="PuntajeVideo1Normal">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo2">ID Video 2</label>
                            <input type="text" class="form-control" name="IdVideo2">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo2">Título Video 2</label>
                            <input type="text" class="form-control" name="TituloVideo2">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo2Estreno">Puntaje Estreno Video 2</label>
                            <input type="text" class="form-control" name="PuntajeVideo2Estreno">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo2Normal">Puntaje Normal Video 2</label>
                            <input type="text" class="form-control" name="PuntajeVideo2Normal">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo3">ID Video 3</label>
                            <input type="text" class="form-control" name="IdVideo3">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo3">Título Video 3</label>
                            <input type="text" class="form-control" name="TituloVideo3">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo3Estreno">Puntaje Estreno Video 3</label>
                            <input type="text" class="form-control" name="PuntajeVideo3Estreno">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo3Normal">Puntaje Normal Video 3</label>
                            <input type="text" class="form-control" name="PuntajeVideo3Normal">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo4">ID Video 4</label>
                            <input type="text" class="form-control" name="IdVideo4">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo4">Título Video 4</label>
                            <input type="text" class="form-control" name="TituloVideo4">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo4Estreno">Puntaje Estreno Video 4</label>
                            <input type="text" class="form-control" name="PuntajeVideo4Estreno">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo4Normal">Puntaje Normal Video 4</label>
                            <input type="text" class="form-control" name="PuntajeVideo4Normal">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="IdVideo5">ID Video 5</label>
                            <input type="text" class="form-control" name="IdVideo5">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="TituloVideo5">Título Video 5</label>
                            <input type="text" class="form-control" name="TituloVideo5">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo5Estreno">Puntaje Estreno Video 5</label>
                            <input type="text" class="form-control" name="PuntajeVideo5Estreno">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="PuntajeVideo5Normal">Puntaje Normal Video 5</label>
                            <input type="text" class="form-control" name="PuntajeVideo5Normal">
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
                    <input type="text" class="form-control" name="NombreInstructor" value="">
                </div>
                <div class="form-group">
                    <label for="PuestoInstructor">Puesto</label>
                    <input type="text" class="form-control" name="PuestoInstructor" value="">
                </div>
                <div class="form-group">
                    <label for="BioInstructor">Bio Instructor</label>
                    <textarea class="form-control" name="BioInstructor" id="BioInstructor" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="Correoinstructor">Correo</label>
                    <input type="text" class="form-control" name="Correoinstructor" value="">
                </div>

                <div class="form-group">
                    <label for="DuracionAproximada">Duración Aprox</label>
                    <input type="text" class="form-control" name="DuracionAproximada">
                </div>
                <hr>
                <div class="form-group">
                    <label for="CantidadPreguntasEvaluacion">¿Cuantas preguntas se mostraran?</label>
                    <input type="number" class="form-control" name="CantidadPreguntasEvaluacion">
                </div>
                <div class="form-group">
                    <label for="OrdenarPreguntasEvaluacion">¿Como se ordenarán las preguntas?</label>
                    <select class="form-control" name="OrdenarPreguntasEvaluacion" id="OrdenarPreguntasEvaluacion">
                        <option value="aleatorio">Aleatorio</option>
                        <option value="ordenado">Ordenado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="EvaluacionObligatoria">¿La evaluación es obligatoria?</label>
                    <select class="form-control" name="EvaluacionObligatoria" id="EvaluacionObligatoria">
                        <option value="si">Si</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <hr>
                <h5>Puntajes</h5>
                <div class="form-group">
                    <label for="VisualizarPuntajeEstreno">Visualización al estreno</label>
                    <input type="number" class="form-control" name="VisualizarPuntajeEstreno">
                </div>
                <div class="form-group">
                    <label for="VisualizarPuntajeNormal">Visualización normal</label>
                    <input type="number" class="form-control" name="VisualizarPuntajeNormal">
                </div>
                <div class="form-group">
                    <label for="PreguntasPuntajeEstreno">Puntaje por pregunta al estreno</label>
                    <input type="number" class="form-control" name="PreguntasPuntajeEstreno">
                </div>
                <div class="form-group">
                    <label for="PreguntasPuntajeNormal">Puntaje por pregunta normal</label>
                    <input type="number" class="form-control" name="PreguntasPuntajeNormal">
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaPublicacion">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaPublicacion">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraPublicacion">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraPublicacion">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="HorasEstreno">¿Cuantas horas dura el estreno?</label>
                    <input type="number" class="form-control" name="HorasEstreno">
                </div>
                <hr>
                <hr>
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select class="form-control" name="Estado" id="Estado">
                        <option value="inactivo">Borrador</option>
                        <option value="activo">Publicado</option>
                    </select>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection