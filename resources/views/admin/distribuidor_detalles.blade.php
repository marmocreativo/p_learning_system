@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles del distribuidor: <small>{{$distribuidor->nombre}}</small></h1>
    <div class="row">
        <div class="col-8">
            <table class="table table-stripped">
                <tr>
                    <td>Nombre</td>
                    <td>{{$distribuidor->nombre}}</td>
                </tr>
                <tr>
                    <td>Pais</td>
                    <td>{{$distribuidor->pais}}</td>
                </tr>
                <tr>
                    <td>Region</td>
                    <td>{{$distribuidor->region}}</td>
                </tr>
                <tr>
                    <td>Nivel</td>
                    <td>{{$distribuidor->nivel}}</td>
                </tr>
                <tr>
                    <td>Estado</td>
                    <td>{{$distribuidor->estado}}</td>
                </tr>
        
            </table>
        </div>
        <div class="col-4">
            <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen) }}" alt="Imagen fondo PL">
            <hr>
            <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen_fondo_a) }}" alt="Imagen fondo PL">
            <hr>
            <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen_fondo_b) }}" alt="Imagen fondo PL">
        </div>
    </div>
    
    <a href="{{ route('distribuidores') }}">Lista de distribuidores</a>

@endsection