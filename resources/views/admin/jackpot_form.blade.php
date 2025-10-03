@extends('plantillas/plantilla_admin')

@section('titulo', 'Jackpots')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Formulario Minijuegos <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button"  data-mdb-dropdown-init
                        data-mdb-ripple-init>
                    Cuentas
                </a>
                <ul class="dropdown-menu" aria-labelledby="breadcrumbDropdown">
                    @foreach($cuentas as $cuentaItem)
                        <li>
                            <a class="dropdown-item" href="{{ route('temporadas', ['id_cuenta' => $cuentaItem->id]) }}">
                                {{ $cuentaItem->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">{{$temporada->nombre}}</a> </li>
            <li class="breadcrumb-item">Minijuegos</li>
        </ol>
    </nav>
    <form action="{{ route('jackpots.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo</label>
                    <input type="text" class="form-control" name="Titulo">
                </div>
                <input type="hidden" name="MensajeAntes" value="">
                <input type="hidden" name="MensajeDespues" value="">
                
                
            </div>
            <div class="col-4">
                <h5>Tipo de juego</h5>
               <div class="form-group">
                    <label for="Tipo">Tipo</label>
                    <select class="form-control" name="Tipo" id="Tipo">
                        <option value="jackpot">jackpot</option>
                        <option value="ruleta">ruleta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <option value="activo"  >Activo</option>
                        <option value="inactivo"  >Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="EnTrivia">Permitir incrustar en trivia</label>
                    <select name="EnTrivia" id="EnTrivia" class="form-control">
                        <option value="no"  >No</option>
                        <option value="si"  >Si</option>
                    </select>
                </div>
                <h5>Intentos</h5>
                <div class="form-group">
                    <label for="Intentos">Intentos por jackpot</label>
                    <input type="number" class="form-control" name="Intentos">
                </div>
                <div class="form-group">
                    <label for="Trivia">¿Trivia obligatoria?</label>
                    <select class="form-control" name="Trivia" id="Trivia">
                        <option value="si">Si</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Region">Región</label>
                    <select class="form-control" name="Region" id="Region">
                        <option value="Todas" >Todas</option>
                        <option value="México" >México</option>
                        <option value="RoLA" >RoLA</option>
                    </select>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaPublicacion">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaPublicacion">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraPublicacion">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraPublicacion">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaVigencia">Fecha de finalización</label>
                            <input type="date" class="form-control" name="FechaVigencia">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraVigencia">Hora de finalización</label>
                            <input type="time" class="form-control" name="HoraVigencia">
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection