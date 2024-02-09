@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Formulario de distribuidores</h1>
    <form action="{{ route('distribuidores.store') }}" method="POST">
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