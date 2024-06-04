@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivias')

@section('contenido_principal')
    <h1>Trivias</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('trivias.create', ['id_temporada'=>$_GET['id_temporada']]) }}">Crear Trivia</a>
    <hr>
    <div class="row">
        @foreach ($trivias as $trivia)
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$trivia->titulo}} </h3>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('trivias.show', $trivia->id)}}">Ver contenido</a>
                        <a href="{{route('trivias.resultados', $trivia->id)}}">Ver resultados</a>
                        <a href="{{route('trivias.edit', $trivia->id)}}">Editar</a>
                        <hr>
                        <form action="{{route('trivias.destroy', $trivia->id)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$trivias->links()}}
@endsection