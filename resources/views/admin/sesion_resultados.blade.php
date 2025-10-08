@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Resultados {{$sesion->titulo}} <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{route('sesiones.show', $sesion->id)}}" class="btn btn-primary">Contenido</a>
            <a href="{{route('sesiones.dudas', $sesion->id)}}" class="btn btn-info">Comentarios usuarios</a>
            <a href="{{route('sesiones.resultados_excel', ['id_sesion'=>$sesion->id])}}" class="btn btn-success enlace_descarga_pesado">Resultados Excel</a>
            <a href="{{route('sesiones.reparar', $sesion->id)}}" class="btn btn-outline-danger">Reparar puntaje</a>
            <a href="{{route('sesiones.edit', $sesion->id)}}" class="btn btn-warning">Editar sesión</a>
        </div>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button"  data-mdb-dropdown-init
                        data-mdb-ripple-init>
                    Cuentas
                </a>
                <ul class="dropdown-menu" aria-labelledby="breadcrumbDropdown">
                    @foreach($cuentas as $cuentaItem)
                        <li>
                            <a class="dropdown-item" href="{{ route('temporadas', ['id_cuenta' => $cuentaItem->id]) }}">
                                {{ $cuentaItem->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">{{$temporada->nombre}}</a> </li>
            <li class="breadcrumb-item"><a href="{{ route('sesiones', ['id_temporada'=> $temporada->id]) }}">Sesiones</a> </li>
            <li class="breadcrumb-item">{{$sesion->titulo}}</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-12">
            
            <h5>Datos generales</h5>
            <table class="table table-bordered table-sm">
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
                        <table class="table table-sm">
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
                <tr>
                    <td>Finalizaron Sesion (Total): </td>
                    <td>Finalizaron Sesion (MEX):</td>
                    <td>Finalizaron Sesion (RoLA):</td>
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
                    <th>Visita</th>
                    <th>Visualización</th>
                    <th>Puntaje Visualización</th>
                    <th>Preguntas</th>
                    <th>Puntaje Preguntas</th>
                    <th>Total</th>
                    <th>Control</th>
                </tr>
                @foreach($visualizaciones as $vis)
                
                @php
                    $total = 0;
                @endphp
                <tr>
                    <td>{{$vis->nombre}} {{$vis->apellidos}}</td>
                    <td>{{$vis->nombre_distribuidor}}</td>
                    <td>{{$vis->visita}}</td>
                    <td>{{$vis->fecha_ultimo_video}}</td>
                    <td>{{$vis->puntaje}}</td>
                    @php
                        $total += $vis->puntaje;
                        $preguntas_texto = '';
                        $puntaje_preguntas = 0;
                    @endphp
                    @foreach($preguntas as $pregunta)
                        @php
                            $respuestasFiltradas = $respuestas->filter(function ($respuesta) use ($vis, $pregunta) {
                                return $respuesta->id_usuario == $vis->id_usuario && $respuesta->id_pregunta == $pregunta->id;
                            });
                        @endphp
                        @foreach ($respuestasFiltradas as $respuesta)
                         @php $preguntas_texto .=$respuesta->respuesta_usuario; @endphp
                            @if($respuesta->respuesta_correcta=='correcto') @php $preguntas_texto .=' <i class="fa-solid fa-circle-check"></i>'; @endphp  @else @php  $preguntas_texto .=' <i class="fa-solid fa-circle-xmark"></i>'; @endphp  @endif
                        @endforeach
                    
                        @foreach ($respuestasFiltradas as $respuesta)
                            @php
                                $puntaje_preguntas += $respuesta->puntaje;
                                $total += $respuesta->puntaje;
                            @endphp
                        @endforeach
                    @endforeach
                    <td>{!! $preguntas_texto !!}</td>
                    <td>{{$puntaje_preguntas}}</td>
                    <td>{{$total}}</td>
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