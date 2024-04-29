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
                    <th>Instrucciones</th>
                    <td>{{$logro->instrucciones}}</td>
                </tr>
            </table>
        </div>
        <div class="col-4">
            <h5>Configuraciones</h5>
            <table class="table table-bordered">
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
        <div class="col-4">
        </div>
        <div class="col-8">
            <table class="table table-bordered">
                <tr>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
                @foreach ($participaciones as $participacion)
                <tr>
                    <td>{{$participacion->id_usuario}}</td>
                    <td>{{$participacion->estado}}</td>
                    <td>{{$participacion->fecha_registro}}</td>
                    <td> 
                        <form action="{{route('logros.destroy_participacion', $participacion->id)}}" method="POST">
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