@extends('plantillas/plantilla_admin')

@section('titulo', 'Inicio')

@section('contenido_principal')
    <h1>Respalda la base de datos</h1>

    <a href="{{ route('admin.backup') }}" class="btn btn-primary">Realizar respaldo</a>

@endsection