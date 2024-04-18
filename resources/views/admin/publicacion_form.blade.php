@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Formulario de publicaciones</h1>
    <form action="{{ route('publicaciones.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        <input type="hidden" name="Clase" value="{{$_GET['clase']}}">
        @csrf
        <div class="form-group">
            <label for="Titulo">Titulo de la publicación</label>
            <input type="text" class="form-control" name="Titulo">
        </div>
        <div class="form-group">
            <label for="Url">URL de la publicación</label>
            <input type="text" class="form-control" name="Url">
        </div>
        <div class="form-group">
            <label for="Descripcion">Descripción corta</label>
            <textarea name="Descripcion" class="form-control"rows="10"></textarea>
        </div>
        <div class="form-group">
            <label for="Contenido">Contenido</label>
            <textarea name="Contenido" class="form-control TextEditor" rows="20"></textarea>
        </div>
        <div class="form-group">
            <label for="Keywords">Keywords</label>
            <textarea name="Keywords" class="form-control"rows="10"></textarea>
        </div>
        <div class="form-group">
            <label for="Destacar">Destacar</label>
            <select name="Destacar" id="Destacar" class="form-control">
                <option value="si">Si</option>
                <option value="no">No</option>
            </select>
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