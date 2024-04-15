@extends('plantillas/plantilla_admin')

@section('titulo', 'Notificaciones')

@section('contenido_principal')
    <h1>Formulario de notificacion</h1>
    <form action="{{ route('notificaciones.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        @csrf
        <div class="form-group">
            <label for="Titulo">Titulo</label>
            <input type="text" class="form-control" name="Titulo">
        </div>
        <div class="form-group">
            <label for="Contenido">Contenido</label>
            <textarea name="Contenido" class="form-control" rows="10"></textarea>
        </div>
        <div class="form-group">
            <label for="MostrarEn">Mostrar en </label>
            <select name="MostrarEn" id="MostrarEn" class="form-control">
                <option value="inicio">Solo inicio</option>
                <option value="todo">Todo el sitio</option>
            </select>
        </div>
        <input type="hidden" name="MostrarEnId" value="">
        <div class="form-group">
            <label for="TipoMensaje">Tipo Mensaje </label>
            <select name="TipoMensaje" id="TipoMensaje" class="form-control">
                <option value="popup">Pop Up</option>
                <option value="pagina">Pagina</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Permanencia">Permanencia </label>
            <select name="Permanencia" id="Permanencia" class="form-control">
                <option value="siempre">Siempre</option>
                <option value="al_inicio">Solo al inicio</option>
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
            <label for="FechaPublicación">Fecha de Publicación</label>
            <input type="date" class="form-control" name="FechaPublicación">
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection