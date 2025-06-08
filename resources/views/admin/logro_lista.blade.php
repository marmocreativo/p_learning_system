@extends('plantillas/plantilla_admin')

@section('titulo', 'Logros')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Desafios <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{ route('logros.create', ['id_temporada'=>$_GET['id_temporada']]) }}" class="btn btn-success">Crear Desafio</a>
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
            <li class="breadcrumb-item">Desafios</li>
        </ol>
    </nav>
    <div class="row">
        @foreach ($logrosSinDistribuidor as $logro)
            <div class="col-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <img class="img-fluid" src="{{ asset('img/publicaciones/'.$logro->imagen) }}" >
                        <h3>{{$logro->nombre}} <small>({{$logro->orden}})</small></h3>
                        <p><span class="badge bg-primary">{{$logro->nivel_usuario}}</span> | <span class="badge @if($logro->region=='México'){{'bg-success'}} @else {{'bg-info'}} @endif">{{$logro->region}} </span> </p>
                        <p>({{$logro->sesiones}})</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('logros.show', $logro->id)}}">Ver contenido</a>
                        <a href="{{route('logros.edit', $logro->id)}}">Editar</a>
                        <hr>
                        <form action="{{route('logros.destroy', $logro->id)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    
    <hr>

    @foreach ($distribuidores as $distribuidor)
        <h2>Distribuidor: {{ $distribuidor->nombre }}</h2>
        <div class="row">
            @forelse ($logrosPorDistribuidor[$distribuidor->id] as $logro)
                <div class="col-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <img class="img-fluid" src="{{ asset('img/publicaciones/'.$logro->imagen) }}" >
                            <img class="img-fluid" src="{{ asset('img/publicaciones/'.$logro->imagen_fondo) }}" >
                            <img class="img-fluid" src="{{ asset('img/publicaciones/'.$logro->tabla_mx) }}" >
                            <img class="img-fluid" src="{{ asset('img/publicaciones/'.$logro->tabla_rola) }}" >
                            <h3>{{$logro->nombre}} <small>({{$logro->orden}})</small></h3>
                            <p><span class="badge bg-primary">{{$logro->nivel_usuario}}</span> | <span class="badge @if($logro->region=='México'){{'bg-success'}} @else {{'bg-info'}} @endif">{{$logro->region}} </span> </p>
                            <p>({{$logro->sesiones}})</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('logros.show', $logro->id)}}">Ver contenido</a>
                            <a href="{{route('logros.edit', $logro->id)}}">Editar</a>
                            <hr>
                            <form action="{{route('logros.destroy', $logro->id)}}" class="form-confirmar" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-link">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>No hay logros asignados a este distribuidor.</p>
            @endforelse
        </div>
        <hr>
    @endforeach
    
@endsection