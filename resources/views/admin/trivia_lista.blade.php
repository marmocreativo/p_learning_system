@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivias')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Trivias <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <a href="{{ route('trivias.create', ['id_temporada'=>$_GET['id_temporada']]) }}" class="btn btn-success">Crear trivia</a>
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
            <li class="breadcrumb-item">Trivias</li>
        </ol>
    </nav>

    <div class="row">
        @foreach ($trivias as $trivia)
            <div class="col-3">
                @php
                    // Obtener la fecha de hoy
                    $today = \Carbon\Carbon::now()->toDateString();
                    $fecha_publicacion = \Carbon\Carbon::parse($trivia->fecha_publicacion)->toDateString();
                    $fecha_vigencia = \Carbon\Carbon::parse($trivia->fecha_vigencia)->toDateString();
                    // Comparar la fecha de publicación con la fecha actual
                    $isBetweenDates = $trivia->fecha_publicacion <= $today && $today <= $trivia->fecha_vigencia;
                @endphp
                <div class="card mb-4">
                    <div class="card-header {{ $isBetweenDates ? 'bg-primary text-white' : '' }}">
                        <h5 title="{{$trivia->id}}">{{$trivia->titulo}}</h5>
                        <p>{{$trivia->fecha_publicacion}}</p>
                    </div>
                    <div class="card-body">
                        <tbody>
                            <tr>
                                <td>Puntaje</td>
                                <td>{{$trivia->puntaje}}</td>
                            </tr>
                        </tbody>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <a href="{{route('trivias.show', $trivia->id)}}" class="btn btn-primary">Ver contenido</a>
                        </div>
                        <div>
                            <form action="{{route('trivias.destroy', $trivia->id)}}" class="form-confirmar" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-link">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$trivias->links()}}
@endsection