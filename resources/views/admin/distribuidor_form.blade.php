@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Formulario de distribuidores</h1>
    <form action="{{ route('distribuidores.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Nombre">Nombre del distribuidor</label>
                    <input type="text" class="form-control" name="Nombre">
                </div>
                <div class="form-group">
                    <label for="Pais">Pais</label>
                    <input type="text" class="form-control" name="Pais">
                </div>
                <div class="form-group">
                    <label for="DefaultPass">Contraseña Default</label>
                    <input type="text" class="form-control" name="DefaultPass">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>
                <div class="form-group">
                    <label for="ImagenFondoA">Portada PLE</label>
                    <input type="file" class="form-control" name="ImagenFondoA" >
                </div>
                <div class="form-group">
                    <label for="ImagenFondoB">PORTADA PL</label>
                    <input type="file" class="form-control" name="ImagenFondoB" >
                </div>
                <hr>
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
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection