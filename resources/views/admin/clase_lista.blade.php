@extends('plantillas/plantilla_admin')

@section('titulo', 'Clases del sistema')

@section('contenido_principal')
    <h1>Clases del sistema</h1>
    <a href="{{ route('clases.create') }}">Crear Clase</a>
    <ul>
        @foreach ($clases as $clase)
            <li>{{$clase->nombre_singular}}</li>
        @endforeach
        
    </ul>
    {{$clases->links()}}
@endsection