@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Formulario de distribuidores</h1>
    <form action="{{ route('distribuidores.update',$distribuidor->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="Nombre">Nombre del distribuidor</label>
            <input type="text" class="form-control" name="Nombre" value="{{$distribuidor->nombre}}">
        </div>
        <div class="form-group">
            <label for="Pais">Pais</label>
            <input type="text" class="form-control" name="Pais" value="{{$distribuidor->pais}}">
        </div>
        <div class="form-group">
            <label for="DefaultPass">Contraseña Default</label>
            <input type="text" class="form-control" name="DefaultPass" value="{{$distribuidor->default_pass}}">
        </div>
        
        <div class="form-group">
            <label for="Region">Region</label>
            <select name="Region" id="Region" class="form-control">
                <option value="" <?php if($distribuidor->region==''){ echo 'selected'; } ?>>Ningúna</option>
                <option value="Interna" <?php if($distribuidor->region=='Interna'){ echo 'selected'; } ?>>Interna</option>
                <option value="México" <?php if($distribuidor->region=='México'){ echo 'selected'; } ?>>México</option>
                <option value="RoLA" <?php if($distribuidor->region=='RoLA'){ echo 'selected'; } ?>>RoLA</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Nivel">Nivel</label>
            <select name="Nivel" id="Nivel" class="form-control">
                <option value="Oyente" <?php if($distribuidor->nivel=='Oyente'){ echo 'selected'; } ?>>Oyente</option>
                <option value="Básico" <?php if($distribuidor->nivel=='Básico'){ echo 'selected'; } ?>>Básico</option>
                <option value="Medio" <?php if($distribuidor->nivel=='Medio'){ echo 'selected'; } ?>>Medio</option>
                <option value="Completo" <?php if($distribuidor->nivel=='Completo'){ echo 'selected'; } ?>>Completo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Estado"></label>
            <select name="Estado" id="Estado" class="form-control">
                <option value="activo" <?php if($distribuidor->estado=='activo'){ echo 'selected'; } ?>>Activo</option>
                <option value="inactivo" <?php if($distribuidor->estado=='inactivo'){ echo 'selected'; } ?>>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@endsection