@extends('plantillas/plantilla_admin')

@section('titulo', 'Jackpots')

@section('contenido_principal')
    <h1>Detalles del Jackpot: <small>{{$jackpot->titulo}}</small></h1>
    <pre>
        <?php var_dump($jackpot); ?>
    </pre>
    <a href="{{ route('jackpots', ['id_temporada'=>$jackpot->id_temporada]) }}">Lista de jackpots</a>

@endsection