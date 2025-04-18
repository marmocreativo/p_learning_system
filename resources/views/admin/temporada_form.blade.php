@extends('plantillas/plantilla_admin')

@section('titulo', 'Temporadas')

@section('contenido_principal')
    <h1>Formulario de Temporadas</h1>
    <form action="{{ route('temporadas.store') }}" method="POST">
        @csrf
        <input type="hidden" name="IdCuenta" value='{{$_GET['id_cuenta']}}'>
        <div class="form-group">
            <label for="Nombre">Nombre de la temporada</label>
            <input type="text" class="form-control" name="Nombre">
        </div>
        <div class="form-group">
            <label for="Url">URL</label>
            <input type="text" class="form-control" name="Url">
        </div>
        <div class="form-group">
            <label for="Descripcion">Descripción</label>
            <textarea class="form-control" name="Descripcion" id="Descripcion" rows="10"></textarea>
        </div>
        <hr>
        <div class="form-group">
            <label for="TituloLanding">Título Landing</label>
            <input type="text" class="form-control" name="TituloLanding">
        </div>
        <div class="form-group">
            <label for="MensajeLanding">Mensaje Landing</label>
            <textarea class="form-control" name="MensajeLanding" id="MensajeLanding" rows="10"></textarea>
        </div>
        <div class="form-group">
            <label for="FechaInicio">Fecha de Inicio</label>
            <input type="date" class="form-control" name="FechaInicio">
        </div>
        <div class="form-group">
            <label for="FechaFinal">Fecha final</label>
            <input type="date" class="form-control" name="FechaFinal">
        </div>
        <div class="form-group">
            <label for="Estado">Estado</label>
            <select name="Estado" id="Estado" class="form-control">
                <option value="activa">Activa</option>
                <option value="inactiva">Inactiva</option>
            </select>
        </div>
        <hr>
        
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection