@extends('plantillas/plantilla_admin')

@section('titulo', 'Temporadas')

@section('contenido_principal')
    <h1>Temporadas</h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Cuentas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">{{$cuenta->nombre}}</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=> $_GET['id_cuenta']]) }}">Temporadas</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <a href="{{ route('temporadas.create',['id_cuenta'=>$_GET['id_cuenta']]) }}">Crear Temporada</a>
        </div>
    </div>
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
                        <form action="{{route('temporadas.destroy', $temporada->id)}}" class="form-confirmar" method="POST">
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