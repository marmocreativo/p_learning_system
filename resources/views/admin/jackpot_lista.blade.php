@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivias')

@section('contenido_principal')
    <h1>Minijuegos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('jackpots.create', ['id_temporada'=>$_GET['id_temporada']]) }}">Crear Jackpot</a>
    <hr>
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
                                    <td><a href="{{route('jackpots.resultados_excel', ['id_jackpot' => $jackpot->id])}}" download="reporte-{{$jackpot->titulo}}" className="btn btn-primary">Resultados Excel</a></td>
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