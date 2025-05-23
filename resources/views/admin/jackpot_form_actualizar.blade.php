@extends('plantillas/plantilla_admin')

@section('titulo', 'Jackpots')

@section('contenido_principal')
    <h1>Formulario de Minijuegos</h1>
    <form action="{{ route('jackpots.update', $jackpot->id) }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$jackpot->id_temporada}}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo</label>
                    <input type="text" class="form-control" name="Titulo" value="{{$jackpot->titulo}}">
                </div>
                <input type="hidden" name="MensajeAntes" value="{{ $jackpot->mensaje_antes }}">
                <input type="hidden" name="MensajeDespues" value="{{ $jackpot->mensaje_despues }}">
                
                
            </div>
            <div class="col-4">
               <h5>Tipo de juego</h5>
               <div class="form-group">
                    <label for="Tipo">Tipo</label>
                    <select class="form-control" name="Tipo" id="Tipo">
                        <option value="jackpot" @if($jackpot->tipo=='jackpot')selected @endif>jackpot</option>
                        <option value="ruleta" @if($jackpot->tipo=='ruleta')selected @endif>ruleta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="EnTrivia">Permitir incrustar en trivia</label>
                    <select name="EnTrivia" id="EnTrivia" class="form-control">
                        <option value="no" @if($jackpot->en_trivia=='no')selected @endif>No</option>
                        <option value="si" @if($jackpot->en_trivia=='si')selected @endif>Si</option>
                    </select>
                </div>
                <h5>Intentos</h5>
                <div class="form-group">
                    <label for="Intentos">Intentos por jackpot</label>
                    <input type="number" class="form-control" name="Intentos" value="{{$jackpot->intentos}}">
                </div>
                <div class="form-group">
                    <label for="Trivia">¿Trivia obligatoria?</label>
                    <select class="form-control" name="Trivia" id="Trivia">
                        <option value="si" @if($jackpot->trivia=='si')selected @endif>Si</option>
                        <option value="no" @if($jackpot->trivia=='no')selected @endif>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Region">Región</label>
                    <select class="form-control" name="Region" id="Region">
                        <option value="Todas" @if($jackpot->region=='Todas')selected @endif>Todas</option>
                        <option value="México" @if($jackpot->region=='México')selected @endif>México</option>
                        <option value="RoLA" @if($jackpot->region=='RoLA')selected @endif>RoLA</option>
                    </select>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="form-group">
                        <label for="Estado">Estado</label>
                        <select name="Estado" id="Estado" class="form-control">
                            <option value="activo" @if($jackpot->estado == 'activo') selected @endif >Activo</option>
                            <option value="inactivo" @if($jackpot->estado == 'inactivo') selected @endif >Inactivo</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaPublicacion">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaPublicacion" value="{{ date('Y-m-d', strtotime($jackpot->fecha_publicacion)) }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraPublicacion">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraPublicacion" value="{{ date('H:i:s', strtotime($jackpot->fecha_publicacion)) }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaVigencia">Fecha de finalización</label>
                            <input type="date" class="form-control" name="FechaVigencia" value="{{ date('Y-m-d', strtotime($jackpot->fecha_vigencia)) }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraVigencia">Hora de finalización</label>
                            <input type="time" class="form-control" name="HoraVigencia" value="{{ date('H:i:s', strtotime($jackpot->fecha_vigencia)) }}">
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection