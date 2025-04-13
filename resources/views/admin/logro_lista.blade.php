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
        @endforeach
    </div>
    {{$logros->links()}}
@endsection