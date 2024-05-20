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
        <div class="col-12">
            <table class="table table-bordered">
                <tr>
                    <th>Usuario</th>
                    <th>Distribuidor</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
                @foreach ($participaciones as $participacion)
                <tr>
                    <td> <a href="{{ route('logros.detalles_participacion', ['id'=>$participacion->id_participacion]) }}">{{$participacion->nombre}} {{$participacion->apellidos}}</a> </td>
                    <td>{{$participacion->nombre_distribuidor}}</td>
                    <td>{{$participacion->estado}}</td>
                    <td>{{$participacion->fecha_registro}}</td>
                    <td> 
                        <form action="{{route('logros.destroy_participacion', $participacion->id_participacion)}}" method="POST">
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