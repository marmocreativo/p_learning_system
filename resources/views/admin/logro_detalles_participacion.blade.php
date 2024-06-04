@extends('plantillas/plantilla_admin')

@section('titulo', 'Logros')

@section('contenido_principal')
    <h1>Detalles del logro: <small>{{$logro->nombre}}</small></h1>
    <a href="{{ route('logros', ['id_temporada'=>$logro->id_temporada]) }}">Lista de logros</a>
    <hr>
    <a href="{{route('logros.edit', $logro->id)}}">Editar logro</a>
    <hr>
    <div class="row">
        <div class="col-8">
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Nombre</th>
                    <td>{{$usuario->nombre}} {{$usuario->apellidos}}</td>
                </tr>
                <tr>
                    <th>Confirmación Nivel A</th>
                    <td>{{$participacion->confirmacion_nivel_a}}</td>
                </tr>
                <tr>
                    <th>Confirmación Nivel B</th>
                    <td>{{$participacion->confirmacion_nivel_b}}</td>
                </tr>
                <tr>
                    <th>Confirmación Nivel C</th>
                    <td>{{$participacion->confirmacion_nivel_c}}</td>
                </tr>
                <tr>
                    <th>Confirmación Nivel Especial</th>
                    <td>{{$participacion->confirmacion_nivel_especial}}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>{{$participacion->estado}}</td>
                </tr>

            </table>
            <h5>Evidencias</h5>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <button class="nav-link active" id="nav-pendientes-tab" data-bs-toggle="tab" data-bs-target="#nav-pendientes" type="button" role="tab" aria-controls="nav-pendientes" aria-selected="true">Pendientes</button>
                  <button class="nav-link" id="nav-nivel-a-tab" data-bs-toggle="tab" data-bs-target="#nav-nivel-a" type="button" role="tab" aria-controls="nav-nivel-a" aria-selected="false">Nivel A</button>
                  <button class="nav-link" id="nav-nivel-b-tab" data-bs-toggle="tab" data-bs-target="#nav-nivel-b" type="button" role="tab" aria-controls="nav-nivel-b" aria-selected="false">Nivel B</button>
                  <button class="nav-link" id="nav-nivel-c-tab" data-bs-toggle="tab" data-bs-target="#nav-nivel-c" type="button" role="tab" aria-controls="nav-nivel-c" aria-selected="false">Nivel C</button>
                  <button class="nav-link" id="nav-nivel-especial-tab" data-bs-toggle="tab" data-bs-target="#nav-nivel-especial" type="button" role="tab" aria-controls="nav-nivel-especial" aria-selected="false">Nivel Especial</button>
                </div>
              </nav>
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-pendientes" role="tabpanel" aria-labelledby="nav-pendientes-tab" tabindex="0">
                    <table class="table table-bordered">
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Verificado</th>
                            <th>Borrar</th>
                        </tr>
                        @foreach ($anexos as $anexo)
                        @if ($anexo->validado=='no')
                        <tr>
                            <td>{{$anexo->documento}}</td>
                            <td>{{$anexo->fecha_registro}}</td>
                            <td>
                                {{$anexo->validado}}<hr>
                                <form action="{{route('logros.actualizar_anexo', $anexo->id)}}" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <label for="Nivel">¿Qué nivel verifica?</label>
                                        <select name="Nivel" class="form-control" id="">
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="especial">Especial</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Validado">¿Validar?</label>
                                        <select name="Validado" class="form-control" id="">
                                            <option value="no">No</option>
                                            <option value="si">Si</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-3">Validar</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{route('logros.destroy_anexo', $anexo->id)}}" class="form-confirmar" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">Borrar</button>
                                </form>
                            </td>
                        </tr>
                        
                        @endif
                         @endforeach
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-nivel-a" role="tabpanel" aria-labelledby="nav-nivel-a-tab" tabindex="0">
                    <table class="table table-bordered">
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Nivel</th>
                            <th>Verificado</th>
                        </tr>
                        @foreach ($anexos as $anexo)
                            @if ($anexo->validado=='si'&&$anexo->nivel=='a')
                            <tr>
                                <td>{{$anexo->documento}}</td>
                                <td>{{$anexo->fecha_registro}}</td>
                                <td>{{$anexo->nivel}}</td>
                                <td>
                                    {{$anexo->validado}}
                                </td>
                            </tr>
                            
                            @endif
                        @endforeach
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-nivel-b" role="tabpanel" aria-labelledby="nav-nivel-b-tab" tabindex="0">
                    <table class="table table-bordered">
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Nivel</th>
                            <th>Verificado</th>
                        </tr>
                        @foreach ($anexos as $anexo)
                            @if ($anexo->validado=='si'&&$anexo->nivel=='b')
                            <tr>
                                <td>{{$anexo->documento}}</td>
                                <td>{{$anexo->fecha_registro}}</td>
                                <td>{{$anexo->nivel}}</td>
                                <td>
                                    {{$anexo->validado}}
                                </td>
                            </tr>
                            
                            @endif
                        @endforeach
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-nivel-c" role="tabpanel" aria-labelledby="nav-nivel-c-tab" tabindex="0">
                    <table class="table table-bordered">
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Nivel</th>
                            <th>Verificado</th>
                        </tr>
                        @foreach ($anexos as $anexo)
                            @if ($anexo->validado=='si'&&$anexo->nivel=='c')
                            <tr>
                                <td>{{$anexo->documento}}</td>
                                <td>{{$anexo->fecha_registro}}</td>
                                <td>{{$anexo->nivel}}</td>
                                <td>
                                    {{$anexo->validado}}
                                </td>
                            </tr>
                            
                            @endif
                        @endforeach
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-nivel-especial" role="tabpanel" aria-labelledby="nav-nivel-especial-tab" tabindex="0">
                    <table class="table table-bordered">
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Nivel</th>
                            <th>Verificado</th>
                        </tr>
                        @foreach ($anexos as $anexo)
                            @if ($anexo->validado=='si'&&$anexo->nivel=='especial')
                            <tr>
                                <td>{{$anexo->documento}}</td>
                                <td>{{$anexo->fecha_registro}}</td>
                                <td>{{$anexo->nivel}}</td>
                                <td>
                                    {{$anexo->validado}}
                                </td>
                            </tr>
                            
                            @endif
                        @endforeach
                    </table>
                </div>
              </div>
              
            
        </div>
        <div class="col-4">
            <h5>Cambiar</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Logro</th>
                    <td>{{$logro->nombre}}</td>
                </tr>
                <tr>
                    <th>Max cantidad de archivos</th>
                    <td>{{$logro->cantidad_evidencias}}</td>
                </tr>
                <tr>
                    <th>Fecha inicio</th>
                    <td>{{$logro->fecha_inicio}}</td>
                </tr>
                <tr>
                    <th>Fecha finalización</th>
                    <td>{{$logro->fecha_vigente}}</td>
                </tr>
            </table>
            @if($participacion->estado !='finalizado')
            <form action="{{ route('logros.participacion_update', $participacion->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="UsuarioEmail" value="{{$usuario->email}}">
                <div class="form-group">
                    <label for="ConfirmacionNivel">Cumple el Nivel?</label>
                    <select name="ConfirmacionNivel" id="" class="form-control">
                        <option value="a" @if($participacion->confirmacion_nivel_a=='si') selected @endif>A</option>
                        <option value="b" @if($participacion->confirmacion_nivel_b=='si') selected @endif>B</option>
                        <option value="c" @if($participacion->confirmacion_nivel_c=='si') selected @endif>C</option>
                        <option value="especial" @if($participacion->confirmacion_nivel_especial=='si') selected @endif>ESPECIAL</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="" class="form-control">
                        <option value="participante" @if($participacion->estado=='participante') selected @endif>Participante</option>
                        <option value="validando" @if($participacion->estado=='validando') selected @endif>Arbitro / Validación</option>
                        <option value="finalizado" @if($participacion->estado=='finalizado') selected @endif>Finalizado</option>
                    </select>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
            @endif
        </div>
    </div>
    

@endsection