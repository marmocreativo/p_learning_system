@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Formulario de publicaciones</h1>
    <form action="{{ route('publicaciones.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="Titulo">Titulo de la publicación</label>
            <input type="text" class="form-control" name="Titulo" value="{{$publicacion->titulo}}">
        </div>
        <div class="form-group">
            <label for="Url">URL de la publicación</label>
            <input type="text" class="form-control" name="Url" value="{{$url->titulo}}">
        </div>
        <div class="form-group">
            <label for="Descripcion">Descripción corta</label>
            <textarea name="Descripcion" class="form-control"rows="10">value="{{$publicacion->descripcion}}"</textarea>
        </div>
        <div class="form-group">
            <label for="Contenido">Contenido</label>
            <textarea name="Contenido" class="form-control"rows="20">{{$publicacion->contenido}}</textarea>
        </div>
        <div class="form-group">
            <label for="Keywords">Keywords</label>
            <textarea name="Keywords" class="form-control"rows="10">{{$publicacion->keywords}}</textarea>
        </div>
        <div class="form-group">
            <label for="Clase">Clase de publicacion</label>
            <select class="form-control" name="Clase">
                @foreach ($clases as $clase)
                    <option value="{{ $clase->nombre_sistema }}" <?php if($clase->nombre_sistema==$publicacion->clase){ echo 'selected'; } ?>> {{ $clase->nombre_singular}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="Destacar">Destacar</label>
            <select name="Destacar" id="Destacar" class="form-control">
                <option value="si" <?php if($publicacion->destacar=='si'){ echo 'selected'; } ?>>Si</option>
                <option value="no" <?php if($publicacion->destacar=='no'){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Estado">Estado</label>
            <select name="Estado" id="Estado" class="form-control">
                <option value="activo" <?php if($publicacion->estado=='activo'){ echo 'selected'; } ?>>Activo</option>
                <option value="inactivo" <?php if($publicacion->estado=='inactivo'){ echo 'selected'; } ?>>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection