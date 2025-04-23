@extends('plantillas/plantilla_admin')

@section('titulo', 'Logros')

@section('contenido_principal')
    <h1>Detalles del desafío: <small>{{$logro->nombre}}</small></h1>
    <a href="{{ route('logros', ['id_temporada'=>$logro->id_temporada]) }}">Lista de desafios</a>
    <hr>
    <a href="{{route('logros.edit', $logro->id)}}">Editar desafio</a>
    <hr>
    <div class="row">
        <div class="col-8">
            <h5>Datos generales</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Nombre</th>
                    <td>{{$logro->nombre}}</td>
                </tr>
                <tr>
                    <th>Premio en texto</th>
                    <td>{{$logro->premio}}</td>
                </tr>
                <tr>
                    <th>Instrucciones</th>
                    <td>{{$logro->instrucciones}}</td>
                </tr>
                <tr>
                    <th>Contenido</th>
                    <td>{{$logro->contenido}}</td>
                </tr>
                <tr>
                    <th>Nivel A<br> {{$logro->nivel_a}}</th>
                    <td>Premio: {{$logro->premio_a}}</td>
                </tr>
                <tr>
                    <th>Nivel B<br>{{$logro->nivel_b}}</th>
                    <td>Premio: {{$logro->premio_b}}</td>
                </tr>
                <tr>
                    <th>Nivel C<br>{{$logro->nivel_c}}</th>
                    <td>Premio: {{$logro->premio_c}}</td>
                </tr>
                <tr>
                    <th>Nivel Especial<br>{{$logro->nivel_especial}}</th>
                    <td>Premio: {{$logro->premio_especial}}</td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <h5>Configuraciones</h5>
            <table class="table table-bordered">
                <tr>
                    <td colspan="2" class="text-center">
                        <img class="img-fluid w-50" src="{{ asset('img/publicaciones/'.$logro->imagen) }}" >
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <img class="img-fluid" src="{{ asset('img/publicaciones/'.$logro->imagen_fondo) }}">
                    </td>
                </tr>
                <tr>
                    <th>Disponible para usuarios</th>
                    <td>{{$logro->nivel_usuario}}</td>
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
        </div>
    </div>
    <hr>
    <div class="row">
        <h4>Participaciones</h4>
        <hr>
        <form action="{{ route('logros.reporte') }}" method="GET" class="row g-3 align-items-end my-4">
            <input type="hidden" name="id_temporada" value="{{ $logro->id_temporada }}">
            <input type="hidden" name="id_logro" value="{{ $logro->id }}">
        
            <div class="col-md-4">
                <label for="region" class="form-label">Selecciona una región</label>
                <select name="region" id="region" class="form-select" required>
                    <option value="">-- Selecciona --</option>
                    <option value="México">México</option>
                    <option value="RoLA">RoLA</option>
                    <option value="Interna">Interna</option>
                </select>
            </div>
        
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">
                    Descargar EXCEL
                </button>
            </div>
        </form>
        <hr>
        <div class="col-12">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Distribuidor</th>
                    <th>Archivos a revisar</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
                @if ($logro->participaciones->isEmpty())
                    <p>No hay participaciones disponibles.</p>
                @else
                    @foreach ($logro->participaciones as $participacion)
                        <tr>
                            <td>{{$participacion->id}}</td>
                            <td>
                                <a href="{{ route('logros.detalles_participacion', ['id' => $participacion->id]) }}">
                                    {{$participacion->usuario->nombre ?? '—'}} {{$participacion->usuario->apellidos ?? ''}}
                                </a>
                            </td>
                            <td>{{$participacion->distribuidor->nombre ?? '—'}}</td>
                            <td>{{$participacion->anexosNoValidados->count()}}</td>
                            <td>{{$participacion->estado}}</td>
                            <td>{{$participacion->fecha_registro}}</td>
                            <td>
                                <form action="{{route('logros.destroy_participacion',$participacion->id)}}" class="form-confirmar" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif

            </table>
        </div>
    </div>
    

@endsection