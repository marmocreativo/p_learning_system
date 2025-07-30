@extends('plantillas/plantilla_admin')

@section('titulo', 'Publicaciones')

@section('contenido_principal')
    <h1>Formulario de publicaciones</h1>
    <form action="{{ route('publicaciones.store') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        <input type="hidden" name="Clase" value="{{$_GET['clase']}}">
        @csrf
        @switch($_GET['clase'])
            @case('pagina')
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="Titulo">Titulo de la publicación</label>
                            <input type="text" class="form-control" name="Titulo" value="">
                        </div>
                        <input type="hidden" name="Url" value="">
                        <div class="form-group">
                            <label for="Descripcion">Descripción corta</label>
                            <textarea name="Descripcion" class="form-control"rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Contenido">Contenido completo </label>
                            <textarea name="Contenido" class="form-control TextEditor" rows="20"></textarea>
                        </div>
                        <input type="hidden" name="Keywords" value="">
                        <input type="hidden" name="BtnCarruselText" value="">
                        <input type="hidden" name="BtnCarruselLink" value="">
                    </div>
                    <div class="col-4">
        
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
                                <option value="normal" >Publicación Normal</option>
                                <option value="terminos" >Términos y condiciones</option>
                                <option value="terminos_champions" >Términos y condiciones de champions</option>
                                <option value="aviso" >Aviso de privacidad</option>
                            </select>
                        </div>
                        <input type="hidden" name="Destacar" value = 'no'>
                        <div class="form-group">
                            <label for="Estado">Estado</label>
                            <select name="Estado" id="Estado" class="form-control">
                                <option value="activo" >Activo</option>
                                <option value="inactivo" >Inactivo</option>
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
                        <input type="text" class="form-control" name="Titulo" value="">
                    </div>
                    <input type="hidden" name="Url" value="">
                    <div class="form-group">
                        <label for="Descripcion">Respuesta</label>
                        <textarea name="Descripcion" class="form-control"rows="3"></textarea>
                    </div>
                    <input type="hidden" name="Contenido" value="">
                    <input type="hidden" name="Keywords" value="">
                    <input type="hidden" name="BtnCarruselText" value="">
                    <input type="hidden" name="BtnCarruselLink" value="">
                </div>
                <div class="col-4">
                    <input type="hidden" name="Funcion" value="normal">
                    <input type="hidden" name="Destacar" value = 'no'>
                    <div class="form-group">
                        <label for="Estado">Estado</label>
                        <select name="Estado" id="Estado" class="form-control">
                            <option value="activo" ?>>Activo</option>
                            <option value="inactivo" ?>>Inactivo</option>
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
                        <input type="text" class="form-control" name="Titulo" value="">
                    </div>
                    <input type="hidden" name="Url" value="">
                    <div class="form-group">
                        <label for="Descripcion">Descripción corta</label>
                        <textarea name="Descripcion" class="form-control"rows="3"></textarea>
                    </div>
                    <input type="hidden" name="Keywords" value="">
                    <input type="hidden" name="Contenido" value="">
                    
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="BtnCarruselText">Botón del carrusel Texto</label>
                                <input type="text" class="form-control" name="BtnCarruselText" value="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="BtnCarruselLink">Botón del carrusel Link</label>
                                <input type="text" class="form-control" name="BtnCarruselLink" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">    
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
                            <option value="activo" >Activo</option>
                            <option value="inactivo" >Inactivo</option>
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