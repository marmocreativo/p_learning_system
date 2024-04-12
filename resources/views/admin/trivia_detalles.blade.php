@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivia')

@section('contenido_principal')
    <h1>Detalles de la trivia: <small>{{$trivia->titulo}}</small></h1>
    <pre>
        <?php var_dump($trivia); ?>
    </pre>
    <a href="{{ route('trivias', ['id_temporada'=>$trivia->id_temporada]) }}">Lista de trivias</a>

@endsection