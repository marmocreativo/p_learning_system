@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Actualizar publicación</h1>
    <form action="{{ route('publicaciones.update', $publicacion->id) }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="Clase" value="{{$publicacion->clase}}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo de la publicación</label>
                    <input type="text" class="form-control" name="Titulo" value="{{$publicacion->titulo}}">
                </div>
                <div class="form-group">
                    <label for="Url">URL de la publicación</label>
                    <input type="text" class="form-control" name="Url" value="{{$publicacion->url}}">
                </div>
                <div class="form-group">
                    <label for="Descripcion">Frente</label>
                    <textarea name="Descripcion" class="form-control"rows="10">{{$publicacion->descripcion}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Vuelta</label>
                    <textarea name="Contenido" class="form-control" rows="20">{{$publicacion->contenido}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Keywords">Link</label>
                    <textarea name="Keywords" class="form-control"rows="10">{{$publicacion->keywords}}</textarea>
                </div>
            </div>
            <div class="col-4">
                <input type="hidden" name="Clase" value='{{$publicacion->clase}}'>

                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>

                <div class="form-group">
                    <label for="ImagenFondo">Imagen tabla de datos</label>
                    <input type="file" class="form-control" name="ImagenFondo" >
                </div>
                <hr>
                <input type="hidden" name="Funcion" value="normal">
                <div class="form-group">
                    <label for="Destacar">Noticia externa</label>
                    <select name="Destacar" id="Destacar" class="form-control">
                        <option value="no" <?php if($publicacion->destacar=='no'){ echo 'selected'; } ?>>no</option>
                        <option value="si" <?php if($publicacion->destacar=='si'){ echo 'selected'; } ?>>si</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <option value="activo" <?php if($publicacion->estado=='activo'){ echo 'selected'; } ?>>Activo</option>
                        <option value="inactivo" <?php if($publicacion->estado=='inactivo'){ echo 'selected'; } ?>>Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        
        
        
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection