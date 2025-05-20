@extends('plantillas/plantilla_admin')

@section('titulo', 'Temporadas')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Temporadas <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <a href="{{ route('temporadas.create', ['id_cuenta' => $_GET['id_cuenta']]) }}" class="btn btn-primary">
            Crear Temporada
        </a>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button"  data-mdb-dropdown-init
                        data-mdb-ripple-init>
                    {{$cuenta->nombre}}
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
            <li class="breadcrumb-item active">Temporadas</li>
        </ol>
    </nav>

    <div class="row">
        @foreach ($temporadas as $temporada)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title mb-1">{{ $temporada->nombre }}</h5>

                            <!-- Menú de tres puntos -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-mdb-dropdown-init
                                    data-mdb-ripple-init>
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('temporadas.edit', $temporada->id) }}">
                                            <i class="fas fa-edit me-2"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('temporadas.destroy', $temporada->id) }}" method="POST" class="form-confirmar">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash-alt me-2"></i> Borrar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="text-muted mb-2">
                            <span class="badge text-uppercase {{ $temporada->estado === 'activa' ? 'bg-success' : 'bg-secondary' }}">{{ $temporada->estado }}</span>
                            @if ($cuenta->temporada_actual==$temporada->id)
                            <span class="badge text-uppercase bg-danger">Temporada actual</span>
                            @endif
                        </p>
                        

                        <a href="{{ route('temporadas.show', $temporada->id) }}" class="btn btn-sm btn-outline-primary d-block mt-2">
                            Ver contenido
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $temporadas->links() }}
    </div>
@endsection
