@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Actualizar la cuenta</h1>
    <form action="{{ route('cuentas.update',$cuenta->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="Nombre">Nombre de la cuenta</label>
            <input type="text" class="form-control" name="Nombre" value="{{$cuenta->nombre}}">
        </div>
        <div class="form-group">
            <label for="Sesiones">Activar sesiones?</label>
            <select name="Sesiones" id="Sesiones" class="form-control">
                <option value="si" <?php if($cuenta->sesiones=='si'){ echo 'selected'; } ?>>Si</option>
                <option value="no" <?php if($cuenta->sesiones=='no'){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Trivias">Activar trivias?</label>
            <select name="Trivias" id="Trivias" class="form-control">
                <option value="si" <?php if($cuenta->trivias=='si'){ echo 'selected'; } ?>>Si</option>
                <option value="no" <?php if($cuenta->trivias=='no'){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Jackpots">Activar Jackpots?</label>
            <select name="Jackpots" id="Jackpots" class="form-control">
                <option value="si" <?php if($cuenta->jackpots=='si'){ echo 'selected'; } ?>>Si</option>
                <option value="no" <?php if($cuenta->jackpots=='no'){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="CanjeoPuntos">Activar canjeo de puntos?</label>
            <select name="CanjeoPuntos" id="CanjeoPuntos" class="form-control">
                <option value="si" <?php if($cuenta->canjeo_puntos=='si'){ echo 'selected'; } ?>>Si</option>
                <option value="no" <?php if($cuenta->canjeo_puntos=='no'){ echo 'selected'; } ?>>No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="TemporadaActual">Temporada Actual</label>
            <input type="text" class="form-control" name="TemporadaActual" value="{{$cuenta->temporada_actual}}">
        </div>
        <div class="form-group">
            <label for="Estado"></label>
            <select name="Estado" id="Estado" class="form-control">
                <option value="activo" <?php if($cuenta->estado=='activo'){ echo 'selected'; } ?>>Activo</option>
                <option value="inactivo" <?php if($cuenta->estado=='inactivo'){ echo 'selected'; } ?>>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection