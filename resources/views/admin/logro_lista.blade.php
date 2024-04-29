@extends('plantillas/plantilla_admin')

@section('titulo', 'Logros')

@section('contenido_principal')
    <h1>Logros</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('logros.create', ['id_temporada'=>$_GET['id_temporada']]) }}">Crear Logro</a>
    <hr>
    <div class="row">
        @foreach ($logros as $logro)
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$logro->nombre}} </h3>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('logros.show', $logro->id)}}">Ver contenido</a>
                        <a href="{{route('logros.edit', $logro->id)}}">Editar</a>
                        <hr>
                        <form action="{{route('logros.destroy', $logro->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$logros->links()}}
@endsection