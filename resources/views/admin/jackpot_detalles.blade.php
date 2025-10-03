@extends('plantillas/plantilla_admin')

@section('titulo', 'Jackpots')

@section('contenido_principal')
<div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Detalles Minijuego {{$jackpot->titulo}}</h1>
        <div class="d-flex">
            <a href="{{ route('jackpots', ['id_temporada'=>$jackpot->id_temporada]) }}" class="btn btn-success">Lista de minijuegos</a>
            <a href="{{route('jackpots.edit', $jackpot->id)}}" class="btn btn-warning">Editar jackpot</a>
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
            <li class="breadcrumb-item">Minijuegos</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-8">
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Título</th>
                    <td>{{$jackpot->titulo}}</td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <h5>Configuraciones</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Estado</th>
                    <td>{{$jackpot->estado}}</td>
                </tr>
                <tr>
                    <th>Intentos</th>
                    <td>{{$jackpot->intentos}}</td>
                </tr>
                <tr>
                    <th>Trivia obligatoria</th>
                    <td>{{$jackpot->trivia}}</td>
                </tr>
                <tr>
                    <th>Fecha publicación</th>
                    <td>{{$jackpot->fecha_publicacion}}</td>
                </tr>
                <tr>
                    <th>Fecha vigencia</th>
                    <td>{{$jackpot->fecha_vigencia}}</td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    @if ($jackpot->en_trivia=='si')
        <div class="text-center bg-light p-4">
            <h4>Este minijuego puede ser insertado en una trivia, no se pueden agregar preguntas.</h4>
        </div>
    @else
    <div class="row">
        <h4>Preguntas Trivia</h4>
            <div class="col-4">
                <form action="{{ route('jackpots.store_pregunta') }}" method="POST">
                    <input type="hidden" name="IdJackpot" value="{{$jackpot->id}}">
                    @csrf
                    <div class="form-group">
                        <label for="Pregunta">Pregunta</label>
                        <input type="text" name="Pregunta" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="RespuestaA">Respuesta A</label>
                        <input type="text" class="form-control" name="RespuestaA">
                    </div>
                    <div class="form-group">
                        <label for="RespuestaB">Respuesta B</label>
                        <input type="text" class="form-control" name="RespuestaB">
                    </div>
                    <div class="form-group">
                        <label for="RespuestaC">Respuesta C</label>
                        <input type="text" class="form-control" name="RespuestaC">
                    </div>
                    <div class="form-group">
                        <label for="RespuestaD">Respuesta D</label>
                        <input type="text" class="form-control" name="RespuestaD">
                    </div>
                    <div class="form-group">
                        <label for="RespuestaCorrecta">Respuesta Correcta</label>
                        <select name="RespuestaCorrecta" class="form-control">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
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
                        <th>Correcta</th>
                        <th>Orden</th>
                        <th>Controles</th>
                    </tr>
                    @foreach ($preguntas as $pregunta)
                    <tr>
                        <td>{{$pregunta->pregunta}}</td>
                        <td>
                            <p>{{$pregunta->respuesta_a}}</p>
                            
                        </td>
                        <td>
                            <p>{{$pregunta->respuesta_b}}</p>
                            
                        </td>
                        <td>
                            <p>{{$pregunta->respuesta_c}}</p>
                            
                        </td>
                        <td>
                            <p>{{$pregunta->respuesta_d}}</p>
                            
                        </td>
                        <td> <b>{{$pregunta->respuesta_correcta}}</b> </td>
                        <td>{{$pregunta->orden}}</td>
                        <td> 
                            <button type="button" class="btn btn-warning" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#formulario{{$pregunta->id}}">
                                Editar
                            </button>
                            <!-- Modal -->
                                <div class="modal fade" id="formulario{{$pregunta->id}}" tabindex="-1" aria-labelledby="formulario{{$pregunta->id}}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form action="{{ route('jackpots.update_pregunta', $pregunta->id) }}" method="POST">
                                                <input type="hidden" name="IdJackpot" value="{{$jackpot->id}}">
                                                @method('PUT')
                                                @csrf
                                                <div class="form-group">
                                                    <label for="Pregunta">Pregunta</label>
                                                    <input type="text" name="Pregunta" class="form-control" value="{{$pregunta->pregunta}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="RespuestaA">Respuesta A</label>
                                                    <input type="text" class="form-control" name="RespuestaA" value="{{$pregunta->respuesta_a}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="RespuestaB">Respuesta B</label>
                                                    <input type="text" class="form-control" name="RespuestaB" value="{{$pregunta->respuesta_b}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="RespuestaC">Respuesta C</label>
                                                    <input type="text" class="form-control" name="RespuestaC" value="{{$pregunta->respuesta_c}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="RespuestaD">Respuesta D</label>
                                                    <input type="text" class="form-control" name="RespuestaD" value="{{$pregunta->respuesta_d}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="RespuestaCorrecta">Respuesta Correcta</label>
                                                    <select name="RespuestaCorrecta" class="form-control">
                                                        <option value="A" @if($pregunta->respuesta_correcta == 'A') selected @endif>A</option>
                                                        <option value="B" @if($pregunta->respuesta_correcta == 'B') selected @endif>B</option>
                                                        <option value="C" @if($pregunta->respuesta_correcta == 'C') selected @endif>C</option>
                                                        <option value="D" @if($pregunta->respuesta_correcta == 'D') selected @endif>D</option>
                                                    </select>
                                                </div>
                                                <hr>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </form>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            <form action="{{route('jackpots.destroy_pregunta', $pregunta->id)}}" method="POST">
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
    @endif
    
    

@endsection