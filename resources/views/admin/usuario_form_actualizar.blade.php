@extends('plantillas/plantilla_admin')

@section('titulo', 'Registra un usuario')

@section('contenido_principal')
    <h1>Formulario de usuario</h1>
    <form action="{{ route('admin_usuarios.update',$usuario->id) }}" method="POST">
        <input type="hidden" name="LegacyId" value="{{$usuario->legacy_id}}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input class="form-control" type="text" name="Nombre" value='{{$usuario->nombre}}'>
        </div>
        <div class="form-group">
            <label for="Apellidos">Apellidos</label>
            <input class="form-control" type="text" name="Apellidos" value='{{$usuario->apellidos}}'>
        </div>
        <div class="form-group">
            <label for="Email">Correo</label>
            <input class="form-control" type="text" name="Email" value='{{$usuario->email}}'>
        </div>
        <div class="form-group">
            <label for="Telefono">Teléfono</label>
            <input class="form-control" type="text" name="Telefono" value='{{$usuario->telefono}}'>
        </div>
        <div class="form-group">
            <label for="Whatsapp">Whatsapp</label>
            <input class="form-control" type="text" name="Whatsapp" value='{{$usuario->whatsapp}}'>
        </div>
        <div class="form-group">
            <label for="FechaNacimiento">Fecha de nacimiento</label>
            <input class="form-control" type="date" name="FechaNacimiento" value='{{$usuario->fecha_nacimiento}}'>
        </div>
        <div class="form-group">
            <label for="Clase">Clase de usuario</label>
            <select class="form-control" name="Clase">
                @foreach ($clases as $clase)
                    <option value="{{ $clase->nombre_sistema }}" <?php if($clase->nombre_sistema==$usuario->clase){ echo 'selected'; }  ?> > {{ $clase->nombre_singular}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="ListaCorreo">Lista de correo</label>
            <select class="form-control" name="ListaCorreo" id="ListaCorreo">
                <option value="no" <?php if($usuario->lista_correo=='no'){ echo 'selected'; }  ?>>No</option>
                <option value="si" <?php if($usuario->lista_correo=='si'){ echo 'selected'; }  ?>>Si</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Estado">Estado</label>
            <select class="form-control" name="Estado" id="Estado">
                <option value="activo" <?php if($usuario->estado=='activo'){ echo 'selected'; }  ?>>Activo</option>
                <option value="inactivo" <?php if($usuario->estado=='inactivo'){ echo 'selected'; }  ?>>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@endsection