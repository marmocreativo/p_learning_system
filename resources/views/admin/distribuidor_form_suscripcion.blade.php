@extends('plantillas/plantilla_admin')

@section('titulo', 'Suscribir un usuario')

@section('contenido_principal')
    <h1>Suscribir Distribuidor</h1>
    <p>*Si el nombre del distribuidor ya está registrado en el sistema solo se agregará la suscripción*</p>
    <form action="{{ route('distribuidores_suscritos.suscribir') }}" method="POST">
        <input type="hidden" name="IdTemporada" value='{{$_GET['id_temporada']}}'>
        <input type="hidden" name="IdCuenta" value='{{$temporada->id}}'>
        @csrf
        <div class="form-group">
            <label for="Nombre">Nombre del distribuidor</label>
            <input type="text" class="form-control" name="Nombre">
        </div>
        <div class="form-group">
            <label for="Pais">Pais</label>
            <input type="text" class="form-control" name="Pais">
        </div>
        
        <div class="form-group">
            <label for="Region">Region</label>
            <select name="Region" id="Region" class="form-control">
                <option value="">Ningúna</option>
                <option value="Interna">Interna</option>
                <option value="México">México</option>
                <option value="RoLA">RoLA</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Nivel">Nivel</label>
            <select name="Nivel" id="Nivel" class="form-control">
                <option value="Oyente">Oyente</option>
                <option value="Básico">Básico</option>
                <option value="Medio">Medio</option>
                <option value="Completo">Completo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Estado"></label>
            <select name="Estado" id="Estado" class="form-control">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection