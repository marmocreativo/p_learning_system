@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la cuenta: <small>{{$cuenta->nombre}}</small></h1>
    <div class="p-4" style="background-color: {{$cuenta->fondo_menu}}">
        <div class="row">
            <div class="col-3">
                <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$cuenta->logotipo) }}" style="max-height:50px" alt="Imagen">
            </div>
            <div class="col-9 pt-3 d-flex justify-content-end">
                <button class="btn btn-primary" style="background-color:{{$cuenta->color_realse}}"> Botón </button>
            </div>
        </div>
    </div>
    <table class="table table-stripped">
        <tr>
            <td>Nombre</td>
            <td>{{$cuenta->nombre}}</td>
        </tr>
        <tr>
            <td>Activar Sesiones</td>
            <td>{{$cuenta->sesiones}}</td>
        </tr>
        <tr>
            <td>Activar Trivias</td>
            <td>{{$cuenta->trivias}}</td>
        </tr>
        <tr>
            <td>Activar Jackpots</td>
            <td>{{$cuenta->jackpots}}</td>
        </tr>
        <tr>
            <td>Activar Canjeo de puntos</td>
            <td>{{$cuenta->canjeo_puntos}}</td>
        </tr>
        <tr>
            <td>Temporada actual</td>
            <td>{{$cuenta->temporada_actual}}</td>
        </tr>
        <tr>
            <td>Estado</td>
            <td>{{$cuenta->estado}}</td>
        </tr>

    </table>
    <a href="{{ route('cuentas') }}">Lista de cuentas</a>

@endsection