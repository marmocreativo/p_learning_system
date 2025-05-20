@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas del sistema')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">Cuentas</h1>
        <a href="{{ route('cuentas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Cuenta
        </a>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cuentas</li>
        </ol>
    </nav>

    <div class="row">
        @foreach ($cuentas as $cuenta)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="p-4 text-center" style="background-color: {{$cuenta->fondo_menu}}">
                    <img src="{{ 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo }}" class="img-fluid" alt="">
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title mb-2">{{ $cuenta->nombre }}</h5>

                            <!-- Menú de tres puntos -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" 
                                    data-mdb-dropdown-init
                                    data-mdb-ripple-init>
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('cuentas.edit', $cuenta->id) }}">
                                            <i class="fas fa-edit me-2"></i> Editar
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('cuentas.destroy', $cuenta->id) }}" method="POST" class="form-confirmar">
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

                        <div class="mt-3">
                            @if ($cuenta->temporada_actual)
                                <a href="{{ route('temporadas.show', $cuenta->temporada_actual) }}" class="btn btn-sm btn-outline-secondary d-block mb-2">
                                    Ver temporada activa
                                </a>
                            @endif

                            <a href="{{ route('temporadas', ['id_cuenta' => $cuenta->id]) }}" class="btn btn-sm btn-outline-secondary d-block">
                                Ver todas las temporadas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $cuentas->links() }}
    </div>
@endsection
