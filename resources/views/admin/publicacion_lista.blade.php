@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Publicaciones</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('publicaciones.create', ['id_temporada'=>$_GET['id_temporada'], 'clase'=>$_GET['clase']]) }}">Crear Publicaciones</a>
    <hr>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>TITULO</th>
            <th>FUNCION</th>
            <th>CONTROLES</th>
            <th>BORRAR</th>
        </tr>
    
        @foreach ($publicaciones as $publicacion)
            <tr>
                <td>{{$publicacion->id}}</td>
                <td>{{$publicacion->titulo}}</td>
                <td>{{$publicacion->funcion}}</td>
                
                <td>
                    <a href="{{route('publicaciones.show', $publicacion->id)}}">Ver detalles</a> |
                    <a href="{{route('publicaciones.edit', $publicacion->id)}}">Editar</a> |
                </td>
                <td>
                    <form action="{{route('publicaciones.destroy', $publicacion->id)}}" class="form-confirmar" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-link">Borrar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    {{$publicaciones->links()}}
@endsection