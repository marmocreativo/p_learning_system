@extends('plantillas/plantilla_admin')

@section('titulo', 'Clases del sistema')

@section('contenido_principal')
    <h1>Formulario de clases</h1>
    <form action="{{ route('clases.update',$clase->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="NombreSistema">Nombre en sistema</label>
            <input type="text" name="NombreSistema" class="form-control" value="{{ $clase->nombre_sistema }}">
        </div>
        <div class="form-group">
            <label for="NombreSingular">Nombre Singular</label>
            <input type="text" name="NombreSingular" class="form-control" value="{{ $clase->nombre_singular }}">
        </div>
        <div class="form-group">
            <label for="NombrePlural">Nombre Plural</label>
            <input type="text" name="NombrePlural" class="form-control" value="{{ $clase->nombre_plural }}">
        </div>
        <div class="form-group">
            <label for="Elementos"></label>
            <select name="Elementos" id="Elementos" class="form-control">
                <option value="publicaciones" <?php if($clase->elementos=='publicaciones'){ echo 'selected'; }  ?>>Publicaciones</option>
                <option value="usuarios" <?php if($clase->elementos=='usuarios'){ echo 'selected'; }  ?>>Usuarios</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@endsection