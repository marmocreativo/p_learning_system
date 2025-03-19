@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Formulario de publicaciones</h1>
    <form action="{{ route('publicaciones.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        <input type="hidden" name="Clase" value="{{$_GET['clase']}}">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo de la publicación</label>
                    <input type="text" class="form-control" name="Titulo">
                </div>
                <div class="form-group">
                    <label for="Url">URL de la publicación</label>
                    <input type="text" class="form-control" name="Url">
                </div>
                <div class="form-group">
                    <label for="Descripcion">Frente</label>
                    <textarea name="Descripcion" class="form-control"rows="10"></textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Vuelta</label>
                    <textarea name="Contenido" class="form-control " rows="20"></textarea>
                </div>
                <div class="form-group">
                    <label for="Keywords">Link</label>
                    <textarea name="Keywords" class="form-control"rows="10">#</textarea>
                </div>
            </div>
            <div class="col-4">
                <input type="hidden" name="Funcion" value="normal">
                <div class="form-group">
                    <label for="Destacar">Noticia externa</label>
                    <select name="Destacar" id="Destacar" class="form-control">
                        <option value="no">no</option>
                        <option value="si">si</option>
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
            </div>
        </div>
        
        
    </form>
@endsection