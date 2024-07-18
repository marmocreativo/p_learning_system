@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Formulario de distribuidores</h1>
    <form action="{{ route('distribuidores.update',$distribuidor->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-8">
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
            </div>
            <div class="col-4">
                <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen) }}" alt="Imagen">
                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>
                <hr>
                <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen_fondo_a) }}" alt="Imagen fondo PLE">
                <div class="form-group">
                    <label for="ImagenFondoA">Portada PLE</label>
                    <input type="file" class="form-control" name="ImagenFondoA" >
                </div>
                <hr>
                <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen_fondo_b) }}" alt="Imagen fondo PL">
                <div class="form-group">
                    <label for="ImagenFondoB">PORTADA PL</label>
                    <input type="file" class="form-control" name="ImagenFondoB" >
                </div>
                <hr>
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
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@endsection