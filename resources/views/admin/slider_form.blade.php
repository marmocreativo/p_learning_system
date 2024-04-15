@extends('plantillas/plantilla_admin')

@section('titulo', 'Sliders')

@section('contenido_principal')
    <h1>Formulario de sliders</h1>
    <form action="{{ route('sliders.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        @csrf
        <div class="form-group">
            <label for="Titulo">Titulo</label>
            <input type="text" class="form-control" name="Titulo">
        </div>
        <div class="form-group">
            <label for="Subtitulo">Subtitulo</label>
            <input type="text" class="form-control" name="Subtitulo">
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="Boton">Texto Botón</label>
                    <input type="text" class="form-control" name="Boton">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="LinkBoton">Link Botón</label>
                    <input type="text" class="form-control" name="LinkBoton">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="Estado">Estado</label>
            <select name="Estado" id="Estado" class="form-control">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection