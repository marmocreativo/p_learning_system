@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones')

@section('contenido_principal')
    <h1>Sesiónes</h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">Temporada</a></li>
                  <li class="breadcrumb-item">Sesiones</li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{ route('sesiones.create', ['id_temporada'=>$_GET['id_temporada']]) }}" class="btn btn-success">Crear Sesión</a>
            </div>
            
        </div>
    </div>
    
    <hr>
    <div class="row">
        @foreach ($sesiones as $sesion)
            @php
                // Obtener la fecha de hoy
                $today = \Carbon\Carbon::now()->toDateString();
                // Comparar la fecha de publicación con la fecha actual
                $isPastOrToday = $sesion->fecha_publicacion <= $today;
            @endphp
            <div class="col-3">
                <div class="card mb-4">
                    <div class="card-header {{ $isPastOrToday ? 'bg-primary text-white' : '' }}">
                        <h5 title="{{$sesion->id}}">{{$sesion->titulo}} </h5>
                        <p>{{$sesion->fecha_publicacion}}</p>
                    </div>
                    <div class="card-body">
                        <table class="table table-stripped">
                            <tbody>
                                <tr>
                                    <th>Acción</th>
                                    <th>Estreno</th>
                                    <th>Normal</th>
                                </tr>
                                <tr>
                                    <td>Visualización</td>
                                    <td>{{$sesion->visualizar_puntaje_estreno}}</td>
                                    <td>{{$sesion->visualizar_puntaje_normal}}</td>
                                </tr>
                                <tr>
                                    <td>Evaluacion</td>
                                    <td>{{$sesion->preguntas_puntaje_estreno}}</td>
                                    <td>{{$sesion->preguntas_puntaje_normal}}</td>
                                </tr>
                                <tr>
                                    <th>Acción</th>
                                    <th>Sin responder</th>
                                    <th>Totales</th>
                                </tr>
                                <tr>
                                    <td>Pregunas especialista</td>
                                    <td>{{$sesion->preguntas_sin_resolver}}</td>
                                    <td>{{$sesion->preguntas}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <a href="{{route('sesiones.show', $sesion->id)}}" class="btn btn-primary">Ver contenido</a>
                        </div>
                        <div>
                            <form action="{{route('sesiones.destroy', $sesion->id)}}" class="form-confirmar" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-outline-danger">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$sesiones->links()}}
@endsection