@extends('plantillas/plantilla_admin')

@section('titulo', 'Temporadas')

@section('contenido_principal')
    <h1>Temporada</h1>
    <hr>
    <a href="{{ route('temporadas.create',['id_cuenta'=>$_GET['id_cuenta']]) }}">Crear Temporada</a>
    <hr>
    <div class="row">
        @foreach ($temporadas as $temporada)
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$temporada->nombre}} </h3>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('temporadas.show', $temporada->id)}}">Ver contenido</a>
                        <a href="{{route('temporadas.edit', $temporada->id)}}">Editar</a>
                        <hr>
                        <form action="{{route('temporadas.destroy', $temporada->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$temporadas->links()}}
@endsection