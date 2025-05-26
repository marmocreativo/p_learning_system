@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">Formulario de cuentas</h1>
        <a href="{{ route('cuentas') }}" class="btn btn-warning">
             Salir
        </a>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cuentas</li>
        </ol>
    </nav>
    <form action="{{ route('cuentas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="Logotipo">Logotipo</label>
            <input type="file" class="form-control" name="Logotipo" >
        </div>
        <div class="form-group">
            <label for="ColorRealse">Color de realse</label>
            <input type="text" class="form-control" name="ColorRealse" value="#F0B323">
        </div>
        <hr>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="FondoMenu">Fondo del menú</label>
                        <input type="text" class="form-control" name="FondoMenu">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="TextoMenu">Texto del menú</label>
                        <input type="text" class="form-control" name="TextoMenu">
                    </div>
                </div>
            </div>
            <hr>
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
            <label for="Jackpots">Activar Top10?</label>
            <select name="Top10" id="Top10" class="form-control">
                <option value="si">Si</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Champions">Activar Champions?</label>
            <select name="Champions" id="Champions" class="form-control">
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
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="BonoLogin">Activar bono de login?</label>
                    <select name="BonoLogin" id="BonoLogin" class="form-control">
                        <option value="si">Si</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="BonoLogin">Puntos por primer login</label>
                    <input type="number" class="form-control" name="BonoLoginCantidad" value="0">
                </div>
            </div>
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