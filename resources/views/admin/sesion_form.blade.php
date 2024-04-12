@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones')

@section('contenido_principal')
    <h1>Formulario de sesiones</h1>
    <form action="{{ route('sesiones.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
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
                    <textarea class="form-control" name="Contenido" id="Contenido" rows="10"></textarea>
                </div>
                <hr>
                
                <!-- Videos -->
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="IdVideo1">ID Video 1</label>
                            <input type="text" class="form-control" name="IdVideo1">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="TituloVideo1">Título Video 1</label>
                            <input type="text" class="form-control" name="TituloVideo1">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="IdVideo2">ID Video 2</label>
                            <input type="text" class="form-control" name="IdVideo2">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="TituloVideo2">Título Video 2</label>
                            <input type="text" class="form-control" name="TituloVideo2">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="IdVideo3">ID Video 3</label>
                            <input type="text" class="form-control" name="IdVideo3">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="TituloVideo3">Título Video 3</label>
                            <input type="text" class="form-control" name="TituloVideo3">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="IdVideo4">ID Video 4</label>
                            <input type="text" class="form-control" name="IdVideo4">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="TituloVideo4">Título Video 4</label>
                            <input type="text" class="form-control" name="TituloVideo4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="IdVideo5">ID Video 5</label>
                            <input type="text" class="form-control" name="IdVideo5">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="TituloVideo5">Título Video 5</label>
                            <input type="text" class="form-control" name="TituloVideo5">
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="NombreInstructor">Instructor</label>
                    <input type="text" class="form-control" name="NombreInstructor">
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
                    <label for="OrdenarPreguntasEvaluacion">¿La evaluación es obligatoria?</label>
                    <select class="form-control" name="OrdenarPreguntasEvaluacion" id="OrdenarPreguntasEvaluacion">
                        <option value="no">No</option>
                        <option value="si">Si</option>
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
                <div class="form-group">
                    <label for="FechaPublicación">Fecha de Publicación</label>
                    <input type="date" class="form-control" name="FechaPublicación">
                </div>
                <div class="form-group">
                    <label for="HorasEstreno">¿Cuantas horas dura el estreno?</label>
                    <input type="number" class="form-control" name="HorasEstreno">
                </div>
                <hr>
                <hr>
                <div class="form-group">
                    <label for="OrdenarPreguntasEvaluacion">Estado</label>
                    <select class="form-control" name="OrdenarPreguntasEvaluacion" id="OrdenarPreguntasEvaluacion">
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