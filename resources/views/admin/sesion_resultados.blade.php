@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la sesión: <small>{{$sesion->titulo}}</small></h1>
    <a href="{{ route('sesiones', ['id_temporada'=>$sesion->id_temporada]) }}">Lista de sesiones</a>
    <hr>
    <a href="{{route('sesiones.edit', $sesion->id)}}">Editar sesión</a>
    <hr>
    <div class="row">
        <div class="col-3">
            <h5>{{date('Y-m-d H:i:s');}}</h5>
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Título</th>
                    <td colspan="2">{{$sesion->titulo}}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td colspan="2">{{$sesion->estado}}</td>
                </tr>
                <tr>
                    <th>Fecha publicación</th>
                    <td colspan="2">{{$sesion->fecha_publicacion}}</td>
                </tr>
                <tr>
                    <th>Horas de estreno</th>
                    <td colspan="2">{{$sesion->horas_estreno}}</td>
                </tr>
                <tr>
                    <th>Cantidad de preguntas para evaluación</th>
                    <td colspan="2">{{$sesion->cantidad_preguntas_evaluacion}}</td>
                </tr>
                <tr>
                    <th>Ordenar preguntas evaluación</th>
                    <td colspan="2">{{$sesion->ordenar_preguntas_evaluacion}}</td>
                </tr>
                <tr>
                    <th>Evaluación obligatoria</th>
                    <td colspan="2">{{$sesion->evaluacion_obligatoria}}</td>
                </tr>
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
        <div class="col-9">
            <table class="table table-bordered table-sm">
                <tr>
                    <th>Usuario</th>
                    <th>Distribuidor</th>
                    <th>Puntaje</th>
                    <th>Visualización</th>
                    <th>Control</th>
                </tr>
                @foreach($visualizaciones as $vis)
                <tr>
                    <td>{{$vis->nombre}} {{$vis->apellidos}}</td>
                    <td>{{$vis->nombre_distribuidor}}</td>
                    <td>{{$vis->puntaje}}</td>
                    <td>{{$vis->fecha_ultimo_video}}</td>
                    <td>
                        <form action="{{route('sesiones.destroy_visualizacion', $vis->id_visualizacion)}}" class="form-confirmar" method="POST">
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
            <table class="table table-bordered table-sm">
                <tr>
                    <th>Usuario</th>
                    <th>Pregunta</th>
                    <th>Respuesta</th>
                        
                    <th>Control</th>
                </tr>
                @foreach ($respuestas as $res)
                <tr>
                    <td>{{$res->nombre}} {{$res->apellidos}}</td>
                    <td>{{$res->pregunta}}</td>
                    <td>
                        {{$res->respuesta_usuario}}<br>
                        {{$res->respuesta_correcta}}
                    </td>
                    <td>{{$res->puntaje}}</td>
                    <td>
                        <form action="{{route('sesiones.destroy_respuesta', $res->id_respuesta)}}" class="form-confirmar" method="POST">
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