@extends('plantillas/plantilla_admin')

@section('titulo', 'Clases del sistema')

@section('contenido_principal')
    <h1>Formulario de clases</h1>
    <form action="{{ route('clases.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="NombreSistema">Nombre en sistema</label>
            <input type="text" name="NombreSistema">
        </div>
        <div class="form-group">
            <label for="NombreSingular">Nombre Singular</label>
            <input type="text" name="NombreSingular">
        </div>
        <div class="form-group">
            <label for="NombrePlural">Nombre Plural</label>
            <input type="text" name="NombrePlural">
        </div>
        <div class="form-group">
            <label for="Elementos"></label>
            <select name="Elementos" id="Elementos">
                <option value="publicaciones">Publicaciones</option>
                <option value="usuarios">Usuarios</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection