@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivia')

@section('contenido_principal')
    <h1>Detalles de la trivia: <small>{{$trivia->titulo}}</small></h1>
    <a href="{{ route('trivias', ['id_temporada'=>$trivia->id_temporada]) }}">Lista de trivias</a>
    <hr>
    <a href="{{route('trivias.edit', $trivia->id)}}">Editar trivia</a>
    <hr>
    <div class="row">
        <div class="col-8">
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Título</th>
                    <td>{{$trivia->titulo}}</td>
                </tr>
                <tr>
                    <th>Descripción</th>
                    <td>{{$trivia->descripcion}}</td>
                </tr>
                <tr>
                    <th>Mensaje Antes</th>
                    <td>{{$trivia->mensaje_antes}}</td>
                </tr>
                <tr>
                    <th>Mensaje Después</th>
                    <td>{{$trivia->mensaje_despues}}</td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <h5>Configuraciones</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Estado</th>
                    <td>{{$trivia->estado}}</td>
                </tr>
                <tr>
                    <th>Puntaje por pregunta</th>
                    <td>{{$trivia->puntaje}}</td>
                </tr>
                <tr>
                    <th>Fecha publicación</th>
                    <td>{{$trivia->fecha_publicacion}}</td>
                </tr>
                <tr>
                    <th>Fecha vigencia</th>
                    <td>{{$trivia->fecha_vigencia}}</td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <h4>Preguntas Trivia</h4>
        <div class="col-4">
            <form action="{{ route('trivias.store_pregunta') }}" method="POST">
                <input type="hidden" name="IdTrivia" value="{{$trivia->id}}">
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
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#formulario{{$pregunta->id}}">
                            Editar
                        </button>
                        <!-- Modal -->
                            <div class="modal fade" id="formulario{{$pregunta->id}}" tabindex="-1" aria-labelledby="formulario{{$pregunta->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{ route('trivias.update_pregunta', $pregunta->id) }}" method="POST">
                                            <input type="hidden" name="IdTrivia" value="{{$trivia->id}}">
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
                                                <label for="ResultadoA">Resultado A</label>
                                                <select name="ResultadoA" class="form-control">
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
                        <form action="{{route('trivias.destroy_pregunta', $pregunta->id)}}" method="POST">
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