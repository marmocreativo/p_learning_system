@extends('plantillas/plantilla_admin')

@section('titulo', 'Suscribir un usuario')

@section('contenido_principal')
    <h1>Suscribir usuario</h1>
    <form action="{{ route('admin_usuarios.suscribir') }}" method="POST">
        <input type="hidden" name="IdTemporada" value='{{$_GET['id_temporada']}}'>
        <input type="hidden" name="IdCuenta" value='{{$temporada->id}}'>
        <input type="hidden" name="IdDistribuidor" value="1">
        @csrf
        <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input class="form-control" type="text" name="Nombre">
        </div>
        <div class="form-group">
            <label for="Apellidos">Apellidos</label>
            <input class="form-control" type="text" name="Apellidos">
        </div>
        <div class="form-group">
            <label for="Email">Correo</label>
            <input class="form-control" type="text" name="Email">
        </div>
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="form-group">
            <label for="Telefono">Teléfono</label>
            <input class="form-control" type="text" name="Telefono">
        </div>
        <div class="form-group">
            <label for="Whatsapp">Whatsapp</label>
            <input class="form-control" type="text" name="Whatsapp">
        </div>
        <div class="form-group">
            <label for="FechaNacimiento">Fecha de nacimiento</label>
            <input class="form-control" type="date" name="FechaNacimiento">
        </div>
        <div class="form-group">
            <label for="Password">Contraseña</label>
            <input class="form-control" type="text" name="Password">
        </div>
        <div class="form-group">
            <label for="Clase">Distribuidor</label>
            <select class="form-control" name="Clase">
                @foreach ($distribuidores as $distribuidor)
                    <option value="{{ $distribuidor->id }}" > {{ $distribuidor->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="Clase">Clase de usuario</label>
            <select class="form-control" name="Clase">
                @foreach ($clases as $clase)
                    <option value="{{ $clase->nombre_sistema }}" > {{ $clase->nombre_singular}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="ListaCorreo">Lista de correo</label>
            <select class="form-control" name="ListaCorreo" id="ListaCorreo">
                <option value="no">No</option>
                <option value="si">Si</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Estado">Estado</label>
            <select class="form-control" name="Estado" id="Estado">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection