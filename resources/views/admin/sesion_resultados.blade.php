@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Resultados de la sesión: <small>{{$sesion->titulo}}</small></h1>
    <div class="row">
        <div class="col-8">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$sesion->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $sesion->id_temporada)}}">Temporada</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('sesiones', ['id_temporada'=>$sesion->id_temporada]) }}">Sesiones</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('sesiones.show', $sesion->id) }}">{{$sesion->titulo}}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Resultados</li>
                </ol>
            </nav>
        </div>
        <div class="col-4">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{route('sesiones.show', $sesion->id)}}" class="btn btn-primary">Contenido</a>
                <a href="{{route('sesiones.dudas', $sesion->id)}}" class="btn btn-info">Dudas</a>
                <a href="{{route('sesiones.resultados_excel', ['id_sesion'=>$sesion->id])}}" class="btn btn-success">Resultados Excel</a>
                <a href="{{route('sesiones.reparar', $sesion->id)}}" class="btn btn-outline-danger">Reparar puntaje</a>
                <a href="{{route('sesiones.edit', $sesion->id)}}" class="btn btn-warning">Editar sesión</a>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <td>
                        Título:<br>
                        {{$sesion->titulo}}
                    </td>
                    <td>
                        Estado:<br>
                        {{$sesion->estado}}
                    </td>
                    <td>
                        Fecha publicación:<br>
                        {{$sesion->fecha_publicacion}}
                    </td>
                    <td rowspan="2">
                        <table class="table">
                            <tr>
                                <th>Puntaje por:</th>
                                <td>Visualización</td>
                                <td>Preguntas</td>
                            </tr>
                            <tr>
                                <th>Estreno</th>
                                <td>{{$sesion->visualizar_puntaje_estreno}}</td>
                                <td>{{$sesion->preguntas_puntaje_estreno}}</td>
                                
                            </tr>
                            <tr>
                                <th>Normal</th>
                                <td>{{$sesion->visualizar_puntaje_normal}}</td>
                                <td>{{$sesion->preguntas_puntaje_normal}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cantidad de preguntas para evaluación:
                        {{$sesion->cantidad_preguntas_evaluacion}}
                    </td>
                    <td>
                        Ordenar preguntas evaluación:
                        {{$sesion->ordenar_preguntas_evaluacion}}
                    </td>
                    <td>
                        Evaluación obligatoria:
                        {{$sesion->evaluacion_obligatoria}}
                    </td>
                </tr>
                
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-sm">
                <tr>
                    <th>Usuario</th>
                    <th>Distribuidor</th>
                    <th>Puntaje</th>
                    <th>Visualización</th>
                    @php $i=1; @endphp
                    @foreach($preguntas as $pregunta)
                        <th>Q{{$i}}</th>
                        <th>Puntaje {{$i}}</th>
                    @php $i++; @endphp
                    @endforeach
                    <th>Control</th>
                </tr>
                @foreach($visualizaciones as $vis)
                <tr>
                    <td>{{$vis->nombre}} {{$vis->apellidos}}</td>
                    <td>{{$vis->nombre_distribuidor}}</td>
                    <td>{{$vis->puntaje}}</td>
                    <td>{{$vis->fecha_ultimo_video}}</td>
                    @foreach($preguntas as $pregunta)
                        @php
                            $respuestasFiltradas = $respuestas->filter(function ($respuesta) use ($vis, $pregunta) {
                                return $respuesta->id_usuario == $vis->id_usuario && $respuesta->id_pregunta == $pregunta->id;
                            });
                        @endphp
                    <td>
                        @foreach ($respuestasFiltradas as $respuesta)
                            {{$respuesta->respuesta_usuario}}
                            @if($respuesta->respuesta_correcta=='correcto') <i class="fa-solid fa-circle-check"></i> @else <i class="fa-solid fa-circle-xmark"></i> @endif
                        @endforeach
                    </td>
                    
                    <td>
                        @foreach ($respuestasFiltradas as $respuesta)
                            {{$respuesta->puntaje}}
                        @endforeach
                    </td>
                    @endforeach
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
    
@endsection