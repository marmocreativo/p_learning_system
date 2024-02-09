@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Formulario de cuentas</h1>
    <form action="{{ route('cuentas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="Nombre">Nombre de la cuenta</label>
            <input type="text" class="form-control" name="Nombre">
        </div>
        <div class="form-group">
            <label for="Sesiones">Activar sesiones?</label>
            <select name="Sesiones" id="Sesiones" class="form-control">
                <option value="si">Si</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Trivias">Activar trivias?</label>
            <select name="Trivias" id="Trivias" class="form-control">
                <option value="si">Si</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Jackpots">Activar Jackpots?</label>
            <select name="Jackpots" id="Jackpots" class="form-control">
                <option value="si">Si</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="CanjeoPuntos">Activar canjeo de puntos?</label>
            <select name="CanjeoPuntos" id="CanjeoPuntos" class="form-control">
                <option value="si">Si</option>
                <option value="no">No</option>
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