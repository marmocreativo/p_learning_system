@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivias')

@section('contenido_principal')
    <h1>Jackpots</h1>
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
                    </div>
                    <div class="card-footer">
                        <a href="{{route('jackpots.show', $jackpot->id)}}">Ver contenido</a>
                        <a href="{{route('jackpots.edit', $jackpot->id)}}">Editar</a>
                        <hr>
                        <form action="{{route('jackpots.destroy', $jackpot->id)}}" method="POST">
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