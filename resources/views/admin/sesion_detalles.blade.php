@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la sesión: <small>{{$sesion->titulo}}</small></h1>
    <pre>
        <?php var_dump($sesion); ?>
    </pre>
    <a href="{{ route('sesiones', ['id_temporada'=>$sesion->id_temporada]) }}">Lista de sesiones</a>

@endsection