@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Sesiónes <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <a href="{{ route('sesiones.create', ['id_temporada'=>$_GET['id_temporada']]) }}" class="btn btn-success">Crear Sesión</a>
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
            <li class="breadcrumb-item">Sesiones</li>
        </ol>
    </nav>
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
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 title="{{$sesion->id}}">{{$sesion->titulo}} </h5>
                                <h6>URL: {{$sesion->url}} </h6>
                                <p>{{$sesion->fecha_publicacion}}</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-mdb-dropdown-init
                                    data-mdb-ripple-init>
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{route('sesiones.edit', $sesion->id)}}">
                                            <i class="fas fa-edit me-2"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{route('sesiones.destroy', $sesion->id)}}" class="form-confirmar" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline-danger">Borrar</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body p-0">
                        <img class="img-fluid" src="{{ 'https://system.panduitlatam.com/img/publicaciones/'.$sesion->imagen_fondo }}" alt="Ejemplo">
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
                                    <td>Pregunas especialista</td>
                                    <td>{{$sesion->preguntas_sin_resolver}}</td>
                                    <td>{{$sesion->preguntas}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('sesiones.show', $sesion->id)}}" class="btn btn-primary w-100">Ver contenido</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$sesiones->links()}}
@endsection