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
            <table class="table table-bordered">
                <tr>
                    <th>Archivo</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
                @foreach ($anexos as $anexo)
                <tr>
                    <td>{{$anexo->documento}}</td>
                    <td>{{$anexo->fecha_registro}}</td>
                    <td> 
                        <form action="{{route('logros.destroy_anexo', $anexo->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Borrar</button>
                        </form>
                    </td>
                </tr>
                 @endforeach
            </table>
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
            <form action="{{ route('logros.participacion_update', $participacion->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="UsuarioEmail" value="{{$usuario->email}}">
                <div class="form-group">
                    <label for="ConfirmacionNivelA">Cumple el Nivel A?</label>
                    <select name="ConfirmacionNivelA" id="" class="form-control">
                        <option value="no" @if($participacion->confirmacion_nivel_a=='no') selected @endif>No</option>
                        <option value="si" @if($participacion->confirmacion_nivel_a=='si') selected @endif>Si</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ConfirmacionNivelB">Cumple el Nivel B?</label>
                    <select name="ConfirmacionNivelB" id="" class="form-control">
                        <option value="no" @if($participacion->confirmacion_nivel_b=='no') selected @endif>No</option>
                        <option value="si" @if($participacion->confirmacion_nivel_b=='si') selected @endif>Si</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ConfirmacionNivelC">Cumple el Nivel C?</label>
                    <select name="ConfirmacionNivelC" id="" class="form-control">
                        <option value="no" @if($participacion->confirmacion_nivel_c=='no') selected @endif>No</option>
                        <option value="si" @if($participacion->confirmacion_nivel_c=='si') selected @endif>Si</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ConfirmacionNivelEspecial">Cumple el Nivel Especial?</label>
                    <select name="ConfirmacionNivelEspecial" id="" class="form-control">
                        <option value="no" @if($participacion->confirmacion_nivel_especial=='no') selected @endif>No</option>
                        <option value="si" @if($participacion->confirmacion_nivel_especial=='si') selected @endif>Si</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="" class="form-control">
                        <option value="participante" @if($participacion->estado=='no') selected @endif>Participante</option>
                        <option value="validando" @if($participacion->estado=='si') selected @endif>Arbitro / Validación</option>
                        <option value="finalizado" @if($participacion->estado=='si') selected @endif>Finalizado</option>
                    </select>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
    

@endsection