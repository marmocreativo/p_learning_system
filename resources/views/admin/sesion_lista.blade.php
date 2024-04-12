@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones')

@section('contenido_principal')
    <h1>Sesiónes</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('sesiones.create', ['id_temporada'=>$_GET['id_temporada']]) }}">Crear Sesión</a>
    <hr>
    <div class="row">
        @foreach ($sesiones as $sesion)
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$sesion->titulo}} </h3>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('sesiones.show', $sesion->id)}}">Ver contenido</a>
                        <a href="{{route('sesiones.edit', $sesion->id)}}">Editar</a>
                        <hr>
                        <form action="{{route('sesiones.destroy', $sesion->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$sesiones->links()}}
@endsection