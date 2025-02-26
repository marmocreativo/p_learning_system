@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Edición de página</h1>
        <div class="row">
            <div class="col-9">
                <nav aria-label="breadcrumb mb-3">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">Temporada</a></li>
                    <li class="breadcrumb-item">Páginas / Publicaciones</li>
                    </ol>
                </nav>
            </div>
            <div class="col-3">
                
            </div>
        </div>
        
        <hr>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <h2>{{$publicacion->titulo}}</h2>
                <hr>
                <p>{{$publicacion->descripcion}}</p>
                <hr>
                {{$publicacion->contenido}}
                <hr>
                <p class="bg-light p-4">{{$publicacion->keywords}}</p>
            </div>
            <div class="col-4">
                <table class="table table-bordered">
                    <tr>
                        <th>Clase</th>
                        <td>{{$publicacion->clase}}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>{{$publicacion->estado}}</td>
                    </tr>
                    <tr>
                        <th>Función</th>
                        <td>{{$publicacion->funcion}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    

@endsection