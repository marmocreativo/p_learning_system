@extends('plantillas/plantilla_admin')

@section('titulo', 'Minijuegos')

@section('contenido_principal')
<div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">MiniJuegos <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <a href="{{ route('jackpots.create', ['id_temporada'=>$_GET['id_temporada']]) }}" class="btn btn-success">Crear Minijuego</a>
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
            <li class="breadcrumb-item">Minijuegos</li>
        </ol>
    </nav>
    <div class="row">
        @foreach ($jackpots as $jackpot)
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$jackpot->titulo}} </h3>
                        <table class="table table-stripped">
                            <tbody>
                                <tr>
                                    <td><a href="{{route('jackpots.show', $jackpot->id)}}">Ver contenido</a></td>
                                </tr>
                                <tr>
                                    <td><a href="{{route('jackpots.resultados', $jackpot->id)}}">Ver resultados</a></td>
                                </tr>
                                <tr>
                                    <td><a href="{{route('jackpots.resultados_excel', ['id_jackpot' => $jackpot->id])}}" download="reporte-{{$jackpot->titulo}}" className="btn btn-primary enlace_pesado">Resultados Excel</a></td>
                                </tr>
                                <tr>
                                    <td><a href="{{route('jackpots.edit', $jackpot->id)}}">Editar</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <form action="{{route('jackpots.destroy', $jackpot->id)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$jackpots->links()}}
@endsection