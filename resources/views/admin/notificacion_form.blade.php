@extends('plantillas/plantilla_admin')

@section('titulo', 'Notificaciones')

@section('contenido_principal')
    <h1>Formulario de notificacion</h1>
    <form action="{{ route('notificaciones.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo</label>
                    <input type="text" class="form-control" name="Titulo">
                </div>
                <div class="form-group">
                    <label for="Contenido">Contenido</label>
                    <textarea name="Contenido" class="form-control TextEditor" rows="10"></textarea>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>
                <div class="form-group">
                    <label for="IdPublicacionMostrar">Cargar contenido de:</label>
                    <select name="IdPublicacionMostrar" id="IdPublicacionMostrar" class="form-control">
                        <option value="">Ningúna</option>
                        @foreach ($publicaciones as $publicacion)
                            <option value="{{$publicacion->id}}">{{$publicacion->titulo}}</option> 
                        @endforeach
                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="MostrarEn">Mostrar en </label>
                    <select name="MostrarEn" id="MostrarEn" class="form-control">
                        <option value="inicio">Solo inicio</option>
                        <option value="temporada_activa">Temporada Activa</option>
                        <option value="temporada">Todas las temporadas</option>
                        <option value="trivia">I love Panduit</option>
                        <option value="jackpot">Jackpot</option>
                        <option value="champions">Champions</option>
                        <option value="todo">Todo el sitio</option>
                    </select>
                </div>
                <input type="hidden" name="MostrarEnId" value="">
                <div class="form-group">
                    <label for="TipoMensaje">Tipo Mensaje </label>
                    <select name="TipoMensaje" id="TipoMensaje" class="form-control">
                        <option value="popup">Pop Up</option>
                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="Permanencia">Permanencia </label>
                    <select name="Permanencia" id="Permanencia" class="form-control">
                        <option value="siempre">Siempre</option>
                        <option value="una_vez_sesion">Una vez por sesión</option>
                        <option value="una_vez_siempre">Solo una vez</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Condicion">Condicion </label>
                    <select name="Condicion" id="Condicion" class="form-control">
                        <option value="ver_publicacion">Ver publicación</option>
                        <option value="click_aceptar">Click en aceptar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="FechaPublicacion">Fecha de Publicación</label>
                    <input type="date" class="form-control" name="FechaPublicacion">
                </div>
                <div class="form-group">
                    <label for="FechaVigencia">Fecha de Vigencia</label>
                    <input type="date" class="form-control" name="FechaVigencia">
                </div>
            </div>
        </div>
        
        <hr>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection