@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Actualizar publicación</h1>
    <form action="{{ route('publicaciones.update', $publicacion->id) }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="Clase" value="{{$publicacion->clase}}">
        @csrf
        @method('PUT')
        @switch($publicacion->clase)
            @case('pagina')
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="Titulo">Titulo de la publicación</label>
                            <input type="text" class="form-control" name="Titulo" value="{{$publicacion->titulo}}">
                        </div>
                        <input type="hidden" name="Url" value="{{$publicacion->url}}">
                        <div class="form-group">
                            <label for="Descripcion">Descripción corta</label>
                            <textarea name="Descripcion" class="form-control"rows="3">{{$publicacion->descripcion}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="Contenido">Contenido completo </label>
                            <textarea name="Contenido" class="form-control TextEditor" rows="20">{{$publicacion->contenido}}</textarea>
                        </div>
                        <input type="hidden" name="Keywords" value="{{$publicacion->keywords}}">
                    </div>
                    <div class="col-4">
                        <input type="hidden" name="Clase" value='{{$publicacion->clase}}'>
        
                        <div class="form-group">
                            <label for="Imagen">Imagen Principal</label>
                            <input type="file" class="form-control" name="Imagen" >
                        </div>
        
                        <div class="form-group">
                            <label for="ImagenFondo">Imagen de fondo</label>
                            <input type="file" class="form-control" name="ImagenFondo" >
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="Funcion">Función</label>
                            <select name="Funcion" id="Funcion" class="form-control">
                                <option value="normal" <?php if($publicacion->funcion=='normal'){ echo 'selected'; } ?>>Publicación Normal</option>
                                <option value="terminos" <?php if($publicacion->funcion=='terminos'){ echo 'selected'; } ?>>Términos y condiciones</option>
                                <option value="terminos_champions" <?php if($publicacion->funcion=='terminos_champions'){ echo 'selected'; } ?>>Términos y condiciones de champions</option>
                                <option value="aviso" <?php if($publicacion->funcion=='aviso'){ echo 'selected'; } ?>>Aviso de privacidad</option>
                            </select>
                        </div>
                        <input type="hidden" name="Destacar" value = 'no'>
                        <div class="form-group">
                            <label for="Estado">Estado</label>
                            <select name="Estado" id="Estado" class="form-control">
                                <option value="activo" <?php if($publicacion->estado=='activo'){ echo 'selected'; } ?>>Activo</option>
                                <option value="inactivo" <?php if($publicacion->estado=='inactivo'){ echo 'selected'; } ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                @break
            @case('faq')
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="Titulo">Pregunta</label>
                        <input type="text" class="form-control" name="Titulo" value="{{$publicacion->titulo}}">
                    </div>
                    <input type="hidden" name="Url" value="{{$publicacion->url}}">
                    <div class="form-group">
                        <label for="Descripcion">Respuesta</label>
                        <textarea name="Descripcion" class="form-control"rows="3">{{$publicacion->descripcion}}</textarea>
                    </div>
                    <input type="hidden" name="Contenido" value="{{$publicacion->contenido}}">
                    <input type="hidden" name="Keywords" value="{{$publicacion->keywords}}">
                </div>
                <div class="col-4">
                    <input type="hidden" name="Funcion" value="normal">
                    <input type="hidden" name="Destacar" value = 'no'>
                    <div class="form-group">
                        <label for="Estado">Estado</label>
                        <select name="Estado" id="Estado" class="form-control">
                            <option value="activo" <?php if($publicacion->estado=='activo'){ echo 'selected'; } ?>>Activo</option>
                            <option value="inactivo" <?php if($publicacion->estado=='inactivo'){ echo 'selected'; } ?>>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
                @break
            @case('noticia')
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="Titulo">Titulo (opcional)</label>
                        <input type="text" class="form-control" name="Titulo" value="{{$publicacion->titulo}}">
                    </div>
                    <input type="hidden" name="Url" value="{{$publicacion->url}}">
                    <div class="form-group">
                        <label for="Descripcion">Descripción corta</label>
                        <textarea name="Descripcion" class="form-control"rows="3">{{$publicacion->descripcion}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="Keywords">Link externo</label>
                        <input type="text" class="form-control" name="Keywords" value="{{$publicacion->keywords}}">
                    </div>
                    <input type="hidden" name="Contenido" value="{{$publicacion->contenido}}">
                </div>
                <div class="col-4">
                    <input type="hidden" name="Clase" value='{{$publicacion->clase}}'>
    
                    <div class="form-group">
                        <label for="Imagen">Imagen Frente</label>
                        <input type="file" class="form-control" name="Imagen" >
                    </div>
                    <div class="form-group">
                        <label for="ImagenFondo">Imagen Vuelta</label>
                        <input type="file" class="form-control" name="ImagenFondo" >
                    </div>
                    <hr>
                    <input type="hidden" name="Funcion" value="normal">
                    <input type="hidden" name="Destacar" value = 'no'>
                    <div class="form-group">
                        <label for="Estado">Estado</label>
                        <select name="Estado" id="Estado" class="form-control">
                            <option value="activo" <?php if($publicacion->estado=='activo'){ echo 'selected'; } ?>>Activo</option>
                            <option value="inactivo" <?php if($publicacion->estado=='inactivo'){ echo 'selected'; } ?>>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
                @break
            @default
            <p>La clase de la publicación no está definida</p>
        @endswitch
        
        
        
        
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection