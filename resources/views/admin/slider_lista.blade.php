@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Sliders</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('sliders.create', ['id_temporada'=>$_GET['id_temporada']]) }}">Crear Slider</a>
    <hr>
    <ul>
        @foreach ($sliders as $slider)
            <li>{{$slider->titulo}} 
                <a href="{{route('sliders.show', $slider->id)}}">Ver detalles</a> |
                <a href="{{route('sliders.edit', $slider->id)}}">Editar</a> |
                <form action="{{route('sliders.destroy', $slider->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Borrar</button>
                </form></li>
        @endforeach
        
    </ul>
    {{$sliders->links()}}
@endsection